<!-- Formular zum earbeiten der Vorlage für ein Bewerbungsanschreiben -->

<template>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info" v-if="recentlySaved">
                Die Änderungen wurden erfolgreich gespeichert.
            </div>
            <div class="alert alert-danger" v-if="saveFailed">
                Beim Speichern ist ein Fehler aufgetreten
            </div>
        </div>
        <!-- Editor für Templates -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- Bearbeite Vorlagen für individuelle Abschnitte -->
                    <div class="card" v-for="(temp) in templates" :key="temp['changed'] + temp['name'] + '_card'">
                        <div class="card-header">
                            <div class="row">
                                <a href="#" class="col-11" @click.prevent="toggle(temp['name'])"><h5>Abschnitt {{ temp['name'] }}</h5></a>
                                <!-- Link zum Löschen eines Abschnitts -->
                                <a v-if="!temp['fix']" href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem">&#128465;</a>
                            </div>
                            <div v-if="!temp['fix']">
                            	<label :for="'number_' + temp['name']">Reihenfolge:</label>
                            	<input type="number" class="border-0" style="width:3em" min="2"
                                   :max="templates.length - 2" :id="'number_' + temp['name']" v-model="temp['number']"/>
                            </div>
                        </div>
                        <div class="card-body" v-show="cards[temp['name']].shown" :key="cards[temp['name']].shown">
                            <!-- fixer Text, in dem nur einige Schlüsselworte ausgewählt werden. -->
                            <div v-if="temp['choose_keywords']">
                                <label :for="'full_text_' + temp['name']">Vorgeschriebener Text:</label>
                                <textarea :id="'full_text_' + temp['name']" class="form-control border-0"
                                          @input="setKwText($event, temp)">{{ getKwText(temp) }}</textarea>
                                <p>Schlüsselworte:</p>
                                <div style="border: solid lightgrey 1px" class="mb-1" 
                                     v-for="(help, index) in temp['keywords']" :key="help['changed'] + '_kw_' + temp['name'] + index">
                                    <div class="row">
                                        <label :for="'kw_heading' + index" class="col-11">Überschrift:</label>
                                        <a href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem">&#128465;</a>
                                    </div>
                                    <input type="text" :id="'kw_heading' + index" class="form-control border-0" v-model="help['heading']"/>
                                    <hr>
                                    <ul>
                                        <li v-for="(kw, ind) in help['tpls']" :key="kw" class="row">
                                            <input type="text" class="form-control border-0 col-11" v-model="help['tpls'][ind]"/>
                                            <a href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem">&#128465;</a>
                                        </li>
                                        <li>
                                            <a href="#">Neues Schlüsselwort</a>
                                        </li>
                                    </ul>
                                </div>
                                <a href="#">Neue Schlüsselwortkategorie</a>
                            </div>

                            <!-- Der gesamte Text ist variabel. -->
                            <div v-else>
                                <label :for="'heading_' + temp['name']">Überschrift:</label>
                                <input type="text" :id="'heading_' + temp['name']" class="form-control border-0" v-model="temp['heading']"/>

                                <p>Templates:</p>
                                <ul>
                                    <li v-for="(help, index) in temp.tpls" :key="help" class="row">
                                        <textarea :id="temp['name'] + '_tpl_' + index" class="form-control border-0 col-11" v-model="temp['tpls'][index]"/>
                                        <a href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem">&#128465;</a>
                                    </li>
                                    <li>
                                        <a href="#">Neues Template</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Link, um Abschnitte hinzuzufügen -->
                    <div class="card">
                        <div class="card-header">
                            <a href="#" @click.prevent="toggle('addSection')"><h5>Abschnitt hinzufügen</h5></a>
                        </div>
                        <div class="card-body" v-show="cards.addSection.shown">
                            <div class="form-group">
                                <label for="addSectionName">Name:</label>
                                <input id="addSectionName" type="text" class="form-control"/>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="addSectionStandard" name="addSectionType" checked/>
                                <label class="custom-control-label" for="addSectionStandard">Standardabschnitt</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="addSectionKeyword" name="addSectionType"/>
                                <label class="custom-control-label" for="addSectionKeyword">Schlüsselwortabschnitt</label>
                            </div>
                            <div class="form-group text-right">
                                <a href="#" class="btn btn-small btn-outline-primary">Abschnitt hinzufügen</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary float-right mx-3">Speichern</button>
                    <button type="button" class="btn btn-outline-primary float-right mx-3">Standard wiederherstellen</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
	props: [],
	
	data() {
		return {
			templates: [],
            cards: {
                addSection: {shown: false}
            },
            recentlySaved: false,
            saveFailed: false
		};
    },
    
    mounted() {
		//Lese zunächst die Templates aus der Datenbank aus
		axios.get(`/bewerbungen/applications/templatesNew`)
            .then(response => response.data).then(data => {
                this.templates = data;
                this.templates.forEach((tpl) => {
                    tpl['changed'] = 0;
                    tpl['keywords'].forEach((kw) => {
                        kw['changed'] = 0;
                    });
                });
                data.forEach((tpl, i) => {
                    this.cards[tpl['name']] = {
                        shown: false,
                        number: tpl['number']
                    };
                });
            });
    },
    
    methods: {
        toggle(name) {
            //card.shown = !card.shown;
            this.cards[name].shown = !this.cards[name].shown;
            //TODO ohne forceUpdate zum Laufen bringen
            this.$forceUpdate();
        },

        //Der Text, in den in einem entsprechenden Abschnitt Schlüsselworte eingesetzt werden können,
        //ist als Array gespeichert. (Getrennt an Stellen, an denen Schlüsselworte eingesetzt werden können.)
        //Methode statt computed properties, da computed properties mir hier Probleme bereiten (im Setter)
        getKwText(tpl) {
            if (tpl['choose_keywords']) {
                return tpl.tpls.join('###');
            }
            else {
                return false;
            }
        },
        setKwText(e, tpl) {
            if (tpl['choose_keywords']) {
                let text = e.target.value;
                tpl.tpls = text.split("###");
            }
        }
    }
};
</script>