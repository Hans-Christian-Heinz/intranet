<!-- Formular zum earbeiten der Vorlage f�r ein Bewerbungsanschreiben -->

<template>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info" v-if="recentlySaved">
                Die �nderungen wurden erfolgreich gespeichert.
            </div>
            <div class="alert alert-danger" v-if="saveFailed">
                Beim Speichern ist ein Fehler aufgetreten
            </div>
        </div>
        <!-- Editor f�r Templates -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- Bearbeite Vorlagen f�r individuelle Abschnitte -->
                    <div class="card" v-for="temp in orderedTpls" :key="temp['changed'] + temp['name'] + '_card'">
                        <div class="card-header">
                            <div class="row">
                                <a href="#" class="col-11" @click.prevent="toggle(temp['name'])"><h5>Abschnitt {{ temp['name'] }}</h5></a>
                                <!-- Link zum L�schen eines Abschnitts -->
                                <a v-if="!temp['fix']" href="#" class="btn text-danger btn-link col-1" 
                                   style="font-size: 1.2rem" @click.prevent="removeSection(temp)">&#128465;</a>
                            </div>
                            <div v-if="!temp['fix']">
                            	<label :for="'number_' + temp['name']">Reihenfolge:</label>
                            	<input type="number" class="border-0" style="width:3em" min="2" @change="changeNumber($event, temp)"
                                   :max="templates.length - 2" :id="'number_' + temp['name']" :value="temp['number']"/>
                            </div>
                        </div>
                        <div class="card-body" v-show="cards[temp['name']].shown" :key="cards[temp['name']].shown">

                            <!-- fixer Text, in dem nur einige Schl�sselworte ausgew�hlt werden. -->
                            <div v-if="temp['choose_keywords']">
                                <label :for="'full_text_' + temp['name']">Vorgeschriebener Text:</label>
                                <textarea :id="'full_text_' + temp['name']" class="form-control border-0"
                                          @input="setKwText($event, temp)">{{ getKwText(temp) }}</textarea>
                                <b>Schl�sselworte:</b>
                                <div style="border: solid lightgrey 1px" class="mb-1" 
                                     v-for="(help, index) in temp['keywords']" :key="help['changed'] + '_kw_' + temp['name'] + index">
                                    <div class="row">
                                        <label :for="'kw_heading' + index" class="col-11">�berschrift:</label>
                                        <a href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem" @click.prevent="removeKwCat(temp, index)">&#128465;</a>
                                    </div>
                                    <input type="text" :id="'kw_heading' + index" class="form-control border-0" v-model="help['heading']"/>
                                    <hr>
                                    <ul>
                                        <li v-for="(kw, ind) in help['tpls']" :key="ind" class="row">
                                            <input type="text" class="form-control border-0 col-11" v-model="help['tpls'][ind]"/>
                                            <a href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem" @click.prevent="removeKw(help, ind)">&#128465;</a>
                                        </li>
                                        <li>
                                            <a href="#" @click.prevent="addKw(help)">Neues Schl�sselwort</a>
                                        </li>
                                    </ul>
                                </div>
                                <a href="#" @click.prevent="addKwCat(temp)">Neue Schl�sselwortkategorie</a>
                            </div>
                            <!-- Der gesamte Text ist variabel. -->
                            <div v-else>
                                <label :for="'heading_' + temp['name']">�berschrift:</label>
                                <input type="text" :id="'heading_' + temp['name']" class="form-control border-0" v-model="temp['heading']"/>

                                <p>Templates:</p>
                                <ul>
                                    <li v-for="(help, index) in temp.tpls" :key="index" class="row">
                                        <textarea :id="temp['name'] + '_tpl_' + index" class="form-control border-0 col-11" v-model="temp['tpls'][index]"/>
                                        <a href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem" @click.prevent="removeTpl(temp, index)">&#128465;</a>
                                    </li>
                                    <li>
                                        <a href="#" @click.prevent="addTpl(temp)">Neues Template</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Formular, um Abschnitte hinzuzuf�gen -->
                    <div class="card">
                        <div class="card-header">
                            <a href="#" @click.prevent="toggle('addSection')"><h5>Abschnitt hinzuf�gen</h5></a>
                        </div>
                        <div class="card-body" v-show="cards.addSection.shown">
                            <div class="form-group">
                                <label for="addSectionName">Name:</label>
                                <input id="addSectionName" type="text" class="form-control"/>
                                <span class="invalid-feedback d-block" v-show="nameError" :key="nameError" role="alert">
                                        <strong>{{ nameError }}</strong>
                                </span>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="addSectionStandard" name="addSectionType" checked/>
                                <label class="custom-control-label" for="addSectionStandard">Standardabschnitt</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="addSectionKeyword" name="addSectionType"/>
                                <label class="custom-control-label" for="addSectionKeyword">Schl�sselwortabschnitt</label>
                            </div>
                            <div class="form-group text-right">
                                <a href="#" class="btn btn-small btn-outline-primary" @click.prevent="addSection()">Abschnitt hinzuf�gen</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary float-right mx-3" @click.prevent="save()">Speichern</button>
                    <button type="button" class="btn btn-outline-primary float-right mx-3" @click.prevent="restoreDefault()">Standard wiederherstellen</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
	props: ["save_route", "restore_default_route"],
	
	data() {
		return {
			templates: [],
            cards: {
                addSection: {shown: false}
            },
            recentlySaved: false,
            saveFailed: false,
            nameError: "",
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
		};
    },

    computed: {
        orderedTpls: function () {
            return _.orderBy(this.templates, 'number')
        }
    },
    
    mounted() {
		//Lese zun�chst die Templates aus der Datenbank aus
		axios.get(`/bewerbungen/applications/templatesNew`)
            .then(response => response.data).then(data => {
                this.templates = data;
                this.templates.forEach((tpl) => {
                    tpl['changed'] = 0;
                    tpl['keywords'].forEach((kw) => {
                        kw['changed'] = 0;
                    });
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

        //Der Text, in den in einem entsprechenden Abschnitt Schl�sselworte eingesetzt werden k�nnen,
        //ist als Array gespeichert. (Getrennt an Stellen, an denen Schl�sselworte eingesetzt werden k�nnen.)
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
        },

        //F�ge einem Abschnitt eine neue Vorlage hinzu
        addTpl(tpl) {
            tpl.tpls.push("Bitte geben Sie eine neue Vorlage ein.");
            tpl.changed ++;
        },

        //Entferne eine Vorlage f�r einen Abschnitt
        removeTpl(tpl, i) {
            tpl.tpls.splice(i, 1);
            tpl.changed ++;
        },

        //F�ge einem Abschnitt eine neue Schl�sselwortkategorie hinzu
        addKwCat(tpl) {
            if (tpl.choose_keywords) {
                let cat = {
                    "number": tpl.keywords.length,
                    "heading": "Bitte geben Sie eine �berschrift ein.",
                    "conjunction": "und",
                    "tpls": [
                        "Bitte geben Sie ein Schl�sselwort ein."
                    ]
                };
                tpl.keywords.push(cat);
                tpl.changed ++;
            }
        },

        //Entferne eine Schl�sselwortkategorie aus einem Abschnitt
        removeKwCat(tpl, i) {
            if (tpl.choose_keywords) {
                tpl.keywords.splice(i, 1);
                tpl.changed ++;
            }
        },

        //F�ge den ausw�hlbaren Schl�sselworten in einer Kategorie eines Abschnitts ein neues Schl�sselwort hinzu
        addKw(cat) {
            cat.tpls.push("Bitte geben Sie ein Schl�sselwort ein.");
            cat.changed ++;
        },

        //Entferne ein Schl�sselwort aus einer Kategorie
        removeKw(cat, i) {
            cat.tpls.splice(i, 1);
            cat.changed ++;
        },

        //F�ge der Vorlage einen neuen Abschnitt hinzu
        addSection() {
            this.nameError = "";

            let kw = document.getElementById('addSectionKeyword').checked;
            let name = $('#addSectionName').val();
            let temp;
            
            //name validieren
            let invalid = !name || !name.trim();
            if (invalid) {
                this.nameError = "Bitte geben Sie einen Namen f�r den Abschnitt ein.";
                return;
            }
            this.templates.forEach(tpl => {
                invalid = invalid || tpl.name === name;
            });
            if (invalid) {
                this.nameError = "Der eingegebene Name ist der Name eines bereits vorhandenen Abschnitts.";
                return;
            }

            //Erstelle einen neuen Schl�sselwortabschnitt
            if (kw) {
                temp = {
                    changed: 0,
                    heading: null,
                    is_heading: false,
                    fix: false,
                    choose_keywords: true,
                    tpls: [
                        "Bitte geben Sie einen Text ein. (Platzhalter f�r Schl�sselworte:) ",
                        " "
                    ],
                    keywords: [
                        {
                            changed: 0,
                            heading: "Bitte f�gen Sie eine �berschrift hinzu.",
                            conjunction: "und",
                            number: 0,
                            tpls: [
                                "Bitte f�gen Sie ein Schl�sselwort hinzu."
                            ]
                        }
                    ]
                };
            }
            //Erstelle einen neuen Standardabschnitt
            else {
                temp = {
                    heading: "Bitte geben Sie eine �berschrift an.",
                    changed: 0,
                    is_heading: false,
                    choose_keywords: false,
                    fix: false,
                    tpls: [
                        "Bitte geben Sie ein Template an."
                    ],
                    keywords: []
                };
            }
            temp.name = name;
            temp.number = this.templates.length - 1;
            this.templates.forEach(tpl => {
                if (tpl.number === this.templates.length - 1) {
                    tpl.number++;
                    tpl.changed++;
                }
            });

            this.templates.push(temp);
            this.cards[name] = {shown: false};
        },

        //Entferne einen Abschnitt aus der Vorlage
        removeSection(tpl) {
            if (!tpl.fix) {
                let index;
                let number = tpl.number;

                this.templates.forEach((temp, i) => {
                    if (temp.number > number) {
                        temp.number--;
                        temp.changed++;
                    }
                    if (temp.name === tpl.name) {
                        index = i;
                    }
                });

                this.templates.splice(index, 1);
            }
        },

        //�ndere die Reihenfolge der Abschnitte (Verschiebe einen Abschnitt und passe die Nummer der restlichen Abschnitte an)
        changeNumber(e, tpl) {
            let old = tpl.number;
            let newVal = e.target.value;
            if (! tpl.fix && newVal > 1 && newVal < this.templates.length - 1) {
                //nach unten verschieben
                if (newVal > old) {
                    this.templates.forEach(temp => {
                        if (temp.number > old && temp.number <= newVal) {
                            temp.number--;
                            temp.changed++;
                        }
                    });
                }
                //nach oben verschieben
                if (newVal < old) {
                    this.templates.forEach(temp => {
                        if (temp.number < old && temp.number >= newVal) {
                            temp.number++;
                            temp.changed++;
                        }
                    });
                }

                tpl.number = newVal;
                tpl.changed++;
            }
        },

        //Speichere die �nderungen an der Vorlage
        save() {
            let route = this.save_route;

            axios.post(route, {templates: this.templates, _method: "patch"})
                .then(response => response.data)
                .then(data => {
                    if (data === false) {
                        this.saveFailed = true;
                        this.recentlySaved = false;
                    }
                    else {
                        this.recentlySaved = true;
                        this.saveFailed = false;

                        setTimeout(() => {
                            this.recentlySaved = false;
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },

        //Stelle die Standardvorlage wieder her
        restoreDefault() {
            let route = this.restore_default_route;

            axios.post(route, {_method: "patch"})
                .then(response => response.data)
                .then(data => {
                    if (data === false) {
                        this.saveFailed = true;
                        this.recentlySaved = false;
                    }
                    else {
                        this.recentlySaved = true;
                        this.saveFailed = false;

                        this.templates = data;
                        this.templates.forEach((tpl) => {
                            tpl['changed'] = 0;
                            tpl['keywords'].forEach((kw) => {
                                kw['changed'] = 0;
                            });
                            this.cards[tpl['name']] = {
                                shown: false,
                                number: tpl['number']
                            };
                        });

                        setTimeout(() => {
                            this.recentlySaved = false;
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
};
</script>