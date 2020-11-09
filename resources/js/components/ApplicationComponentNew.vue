<!-- Komponente zum Erstellen und Bearbeiten von Bewerbungsanschreiben basierend auf einer Vorlage -->

<template>
	<div>
		<div class="row">
			<div class="col-md-12">
				<!-- parent_header existiert, um die Standardposition der Kopfzeile zu behalten -->
				<div id="parent_header"></div>
				<div class="alert alert-info kopfzeile" :class="{ 'fixed-top': fixHeader }" v-if="recentlySaved">
					Die Änderungen wurden erfolgreich gespeichert.
				</div>
				<div class="alert alert-danger kopfzeile" :class="{ 'fixed-top': fixHeader }" v-if="saveFailed">
					Beim Speichern ist ein Fehler aufgetreten
				</div>
			</div>
			<div class="col-md-8">
				<div class="card">
					<div class="card-body" style="max-height: 75vh; overflow-y: scroll;">
						<p v-for="(text, name) in data" :key="name + text.changed">
							<b v-if="text.is_heading">
								<span contenteditable @input="input($event, text)">{{ text.text }}</span>
							</b>
							<span v-else contenteditable @input="input($event, text)">{{ text.text }}</span>
						</p>

						<p>Mit freundlichen Grüßen</p>

						<p>{{ this.user.full_name }}, {{ currentDate }}</p>

                        <p class="mt-2"><b>Anlagen:</b></p>
                        <ul style="list-style: none; padding-inline-start: 0" :key="'displayAtt' + attachments.changed">
                            <li v-for="(att, i) in attachments.values">
                                <input type="text" :id="'displayAtt_' + i" class="form-control border-0 px-0" v-model="attachments.values[i]">
                            </li>
                        </ul>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body" style="max-height: 70vh; overflow-y: scroll;">
                        <div class="form-group">
                            <h5>Variablen</h5>
                            <div v-for="(v, i) in variables">
                                <label :for="'variable' + i">{{ v.name }}</label>
                                <input :id="'variable' + i" type="text" class="form-control" v-model="v.value"/>
                            </div>
                        </div>

                        <hr>

						<div class="form-group" v-for="(tpl) in this.templates">
							<div v-if="tpl.choose_keywords">
								<div v-for="(cat, i) in tpl.keywords">
									<h5>{{ cat.heading }}</h5>

									<div v-for="(kw, j) in cat.tpls">
										<input type="checkbox" :id="tpl.name + i + j" :value="kw" @click="ensureKeyword($event, tpl, i)"
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
										<!--<button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="sr-only">Toggle Dropdown</span>
										</button>
										<div class="dropdown-menu" style="width: 100%; overflow-y: scroll; max-height:30rem;">
											<a class="dropdown-item" style="border-bottom: solid 1px grey" href="#"
												v-for="temp in tpl.tpls" @click.prevent="useTemplate(tpl.name, temp)">
												<span style="word-wrap: break-word; white-space: normal;">{{ temp }}</span>
											</a>
										</div>-->
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="modal" :data-target="'#chooseTplModal' + tpl.name">
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
									</div>
								</div>

                                <!-- modales Fenster zur Auswahl eines Templates -->
                                <div class="modal fade" :id="'chooseTplModal' + tpl.name" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Template auswählen</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <ul style="list-style-type: none;">
                                                    <li>
                                                        <a class="dropdown-item" style="border-bottom: solid 1px grey" href="#"
                                                           v-for="temp in tpl.tpls" @click.prevent="useTemplate(tpl.name, temp)">
                                                            <span style="word-wrap: break-word; white-space: normal;">{{ temp }}</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>

							<hr>

						</div>

                        <h5>Anlagen</h5>
                        <ul :key="attachments.changed">
                            <li v-for="(att, i) in attachments.values">
                                <input type="text" :id="'att_' + i" class="form-control border-0 px-0 w-75 d-inline" v-model="attachments.values[i]">
                                <a href="#" class="btn text-danger btn-link d-inline" @click.prevent="removeAttachment(i)">&#128465;</a>
                            </li>
                            <li>
                                <a href="#" @click.prevent="addAttachment()">Anlage hinzufügen</a>
                            </li>
                        </ul>
                        <hr>
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
	props: ["route", "saved", "user", "version", "application"],

	data () {
		return {
			templates: [],
            variables: [],
			data: {},
            attachments: {
			    values: [],
                changed: 0
            },

			recentlySaved: false,
			saveFailed: false,
			fixHeader: false
		}
	},

	mounted() {
        //Lese die Variablen aus der Datenbank aus
        axios.get(`/bewerbungen/applications/variables`)
            .then(response => response.data).then(data => {
            this.variables = data;
            const app = this;
            this.variables.forEach(function (v) {
                //Format ##(property) wird aufgelöst; die entsprechende Eigenschaft von application (vgl. props) wird übernommen
                if (v.standard.substr(0, 3) === "##(") {
                    let help = v.standard.substr(3, v.standard.length - 4);
                    let properties = help.split('.');
                    let res = JSON.parse(app.application);
                    for (let i = 0; i < properties.length; i++) {
                        res = res[properties[i]];
                    }
                    v.value = res;
                }
                else {
                    v.value = v.standard;
                }
            });
        })
            //lese nun die Templates aus der Datenbank aus
            //nach dem Auslesen der Variablen, da diese ggf schon verwendet werden.
            .then(() => axios.get(`/bewerbungen/applications/templatesNew/` + this.version)
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

                            //Ersetze nun alle Variablen durch ihre Werte dem anzuwendenden Text
                            text = this.replaceVariables(text);

                            this.data[key] = {
                                is_heading: is_heading,
                                text: text,
                                changed: 0
                            };
                        }
                    });
                }));

		if (this.saved.attachments) {
		    this.attachments.values = this.saved.attachments;
        }
		else {
		    this.attachments.values = ['Lebenslauf'];
        }
	},

	created() {
        //lodash throttle: Methode wird nicht öfter als alle 100ms aufgerufen
        //Wenn sie davor aufgerufen wird, wird das Ergebnis nicht neu berechnet
        this.handleThrottledScroll = _.throttle(this.handleScroll, 100);
        window.addEventListener('scroll', this.handleThrottledScroll);
	},

	computed: {
        currentDate() {
            let date = new Date();
            let day = ("0" + date.getDate()).slice(-2);
            let month = ("0" + (date.getMonth() + 1)).slice(-2);
            let year = date.getFullYear();

            return `${day}.${month}.${year}`;
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
                //Stelle sicher, dass nicht null oder undefined oder ein ähnlicher Wert angezeigt wird.
                if (tpl['tpls'][i]) {
                    res += tpl['tpls'][i] + " ";
                }
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
		    let text = this.keywordsText(tpl, values);
            //Ersetze nun alle Variablen durch ihre Werte dem anzuwendenden Text
			this.data[tpl['name']].text = this.replaceVariables(text);
			this.data[tpl.name].changed++;
			//TODO Alternative für forceUpdate suchen
			this.$forceUpdate();
		},

        ensureKeyword(e, tpl, catNr) {
            //Wenn nur ein Schlüsselwort in der Kategorie ausgewählt ist, bleibt es auf jeden Fall ausgewählt.
            if(this.kwValues[tpl['name']][catNr].length === 1 && !e.target.checked) {
                e.preventDefault();
            }
        },

		//Wende ein Template auf einen Abschnitt an
		//name: Name des Abschnitts
		//temp anzuwendendes Template
		useTemplate(name, temp) {
		    this.data[name].text = this.replaceVariables(temp);
			this.data[name].changed++;
			//TODO Alternative für forceUpdate suchen
			this.$forceUpdate();
        },

        /**
         * Ersetzt im übergebenen Text alle Variablen mit ihrem Wert
         */
        replaceVariables(text) {
            this.variables.forEach(function(v) {
                let help = "##(" + v.name.trim() + ")";
                text = text.replaceAll(help, v.value.trim());
            });

            return text;
        },

		//Änderungen speichern
		save() {
			let copy = Object.assign({}, this.data);
			//drop the changed-field for each section
			Object.keys(copy).forEach(key => {
				delete copy[key].changed;
			});
			copy.attachments = this.attachments.values;

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
		},

		handleScroll(e) {
            if ($('.kopfzeile').length) {
                this.fixHeader = window.pageYOffset > $('#parent_header').offset().top;
            }
        },

        addAttachment() {
		    this.attachments.values.push('neue Analage');
		    this.attachments.changed++;
        },

        removeAttachment(i) {
		    this.attachments.values.splice(i, 1);
		    this.attachments.changed++;
        }
	}
}
</script>
