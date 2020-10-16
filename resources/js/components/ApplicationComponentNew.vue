<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-info fixed-top" v-if="recentlySaved">
					Die Änderungen wurden erfolgreich gespeichert.
				</div>
				<div class="alert alert-danger fixed-top" v-if="saveFailed">
					Beim Speichern ist ein Fehler aufgetreten
				</div>
			</div>
			<div class="col-md-8">
				<div class="card">
					<div class="card-body" style="max-height: 100vh; overflow-y: scroll;">
						<p v-for="(text, name) in data" :key="name + text.changed">
							<b v-if="text.is_heading">
								<span contenteditable @input="input($event, text)">{{ text.text }}</span>
							</b>
							<span v-else contenteditable @input="input($event, text)">{{ text.text }}</span>
						</p>

						<p>Mit freundlichen Grüßen</p>

						<p class="mt-5">{{ this.user.full_name }}, {{ currentDate }}</p>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body" style="max-height: 76vh; overflow-y: scroll;">

						<div class="form-group" v-for="(tpl) in this.templates">
							<div v-if="tpl.choose_keywords">
								<div v-for="(cat, i) in tpl.keywords">
									<h5>{{ cat.heading }}</h5>

									<div v-for="(kw, j) in cat.tpls">
										<input type="checkbox" :id="tpl.name + i + j" :value="kw" 
											v-model="kwValues[tpl['name']][i]" @change="applyKwText(tpl, kwValues[tpl['name']])"/>
										<label :for="tpl.name + i + j">{{ kw }}</label>
									</div>
									<hr>
								</div>
							</div>

							<div v-else>
								<h5>{{ tpl.heading }}</h5>

								<div class="input-group">
									<input type="text" class="form-control" v-model="data[tpl.name].text">
									<div class="input-group-append">
										<button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="sr-only">Toggle Dropdown</span>
										</button>
										<div class="dropdown-menu" style="width: 100%; overflow-y: scroll; max-height:30rem;">
											<a class="dropdown-item" style="border-bottom: solid 1px grey" href="#"
												v-for="temp in tpl.tpls" @click.prevent="useTemplate(tpl.name, temp)">
												<span style="word-wrap: break-word; white-space: normal;">{{ temp }}</span>
											</a>
										</div>
									</div>
								</div>
							</div>

							<hr>
						</div>
					</div>
					<div class="card-body d-flex justify-content-center">
						<svg class="bi bi-arrow-down-short" width="1.2em" height="1.2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M4.646 7.646a.5.5 0 01.708 0L8 10.293l2.646-2.647a.5.5 0 01.708.708l-3 3a.5.5 0 01-.708 0l-3-3a.5.5 0 010-.708z" clip-rule="evenodd"/>
							<path fill-rule="evenodd" d="M8 4.5a.5.5 0 01.5.5v5a.5.5 0 01-1 0V5a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
						</svg>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<button type="button" class="btn btn-outline-danger mt-3" data-toggle="modal" data-target="#deleteApplicationModal">Löschen</button>
			</div>
			<div class="col-6">
				<button type="button" class="btn btn-primary float-right mt-3 mx-2" @click="save()">Änderungen speichern</button>
				<a class="btn btn-outline-info float-right mt-3 mx-2" data-toggle="modal" href="#formatPdf">Drucken</a>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: ["route", "saved", "user", "version"],

	data () {
		return {
			templates: [],
			data: {},

			recentlySaved: false,
			saveFailed: false
		}
	},

	mounted() {
		//Lese zunächst die Templates aus der Datenbank aus
		axios.get(`/bewerbungen/applications/templatesNew/` + this.version)
            .then(response => response.data).then(data => {
				this.templates = data;
				
				//Hinterlege einen Wert für den Körper des Bewerbungsanschreibens
				this.templates.forEach(tpl => {
					const key = tpl['name'];
					if (this.saved[key]) {
						this.data[key] = this.saved[key];
						this.data[key].changed = 0;
					}
					else {
						const is_heading = tpl['is_heading'];
						let text;
						if(tpl['choose_keywords']) {
							let values = [];
							for (let i = 0; i < tpl['keywords'].length; i++) {
								values[i] = [];
								//Standardauswahl: die ersten 3 Schlüsselworte
								for (let j = 0; j < Math.min(3, tpl['keywords'][i]['tpls'].length); j++) {
									values[i].push(tpl['keywords'][i]['tpls'][j]);
								}
							}
							text = this.keywordsText(tpl, values);	
						}
						else {
							text = tpl['tpls'][0];
						}

						this.data[key] = {
							is_heading: is_heading,
							text: text,
							changed: 0
						};
					}
				});
            });
	},

	computed: {
        currentDate() {
            let date = new Date();
            let day = ("0" + date.getDate()).slice(-2);
            let month = ("0" + (date.getMonth() + 1)).slice(-2);
            let year = date.getFullYear();

            let dateString = `${day}.${month}.${year}`;

            return dateString;
		},
		
		//ausgewählte Schlüsselworte
		kwValues() {
			let res = {};
			this.templates.forEach(tpl => {
				if (tpl['choose_keywords']) {
					let text = this.data[tpl['name']].text;
					res[tpl['name']] = [];
					for (let i = 0; i < tpl['keywords'].length; i++) {
						res[tpl['name']][i] = [];
						for (let j = 0; j < tpl['keywords'][i]['tpls'].length; j++) {
							if (text.includes(tpl['keywords'][i]['tpls'][j])) {
								res[tpl['name']][i].push(tpl['keywords'][i]['tpls'][j]);
							}
						}
					}
				}
			});

			return res;
		}
    },

	methods: {
		//Eingabe im Anschreiben. (Nicht im Editor rechts)
		input(e, section) {
			section.text = e.target.innerText;
		},

		//Ermittle aus einem template und den ausgewählen Schlüsselworten den fertigen Text eines Abschnitts
		//tpl: Das template für den Abschnitt
		//values: mehrdimensionaler Array (keyword_cat => [kw_1, kw_2...])
		keywordsText(tpl, values) {
			let res = "";
			
            //Gehe den Array text im Template durch. (Der Text, in den Schlüsselworte einzusetzen sind, unterbrochen an
            //den Stellen, an denen Schlüsselworte einzusetzen sind.
            for (let i = 0; i < tpl['tpls'].length; i++) {
                res += tpl['tpls'][i] + " ";
                if (i < values.length) {
                    for (let j = 0; j < values[i].length; j++){
                        res += values[i][j];
                        if (j < values[i].length - 2) {
                            res += ", ";
                        }
                        if (j === values[i].length - 2) {
							let conj;
							tpl['keywords'][i]['conjunction'] ? conj = tpl['keywords'][i]['conjunction'] : conj = ", ";
                            res += " " + conj + " ";
                        }
                    }
                    res = res + " ";
                }
			}
			//Falls mehr Schlüsselwortkategorien als Platzhalter vorliegen, werden die entsprechenden Schlüsselworte am Ende
			//des Abschnitts eingefügt
			for (let i = tpl['tpls'].length; i < values.length; i++) {
				for (let j = 0; j < values[i].length; j++){
                        res += values[i][j];
                        if (j < values[i].length - 2) {
                            res += ", ";
                        }
                        if (j === values[i].length - 2) {
                            res += " " + tpl['keywords'][i].conjunction + " ";
                        }
                    }
                    res = res + " ";
			}

            return res;
		},

		applyKwText(tpl, values) {
			this.data[tpl['name']].text = this.keywordsText(tpl, values);
			this.data[tpl.name].changed++;
			//TODO Alternative für forceUpdate suchen
			this.$forceUpdate();
		},
		
		//Wende ein Template auf einen Abschnitt an
		//name: Name des Abschnitts
		//temp anzuwendendes Template
		useTemplate(name, temp) {
			this.data[name].text = temp;
			this.data[name].changed++;
			//TODO Alternative für forceUpdate suchen
			this.$forceUpdate();
        },

		//Änderungen speichern
		save() {
			let copy = Object.assign({}, this.data);
			//drop the changed-field for each section
			Object.keys(copy).forEach(key => {
				delete copy[key].changed;
			});

			axios.post(this.route, {body: copy, _method: "patch"})
                .then(response => response.data)
                .then(data => {
                    this.recentlySaved = data;

                    setTimeout(() => {
                        this.recentlySaved = false;
                    }, 3000);
                })
                .catch(error => {
                    console.log(error);
                });
		}
	}
}
</script>