<!-- Formular zum earbeiten der Vorlage für ein Bewerbungsanschreiben -->

<template>
    <div class="row">
        <div class="col-md-12">
            <div id="parent_header"></div>
            <div class="alert alert-info kopfzeile" :class="{ 'fixed-top': fixHeader }" v-if="recentlySaved">
                Die Änderungen wurden erfolgreich gespeichert.
            </div>
            <div class="alert alert-danger kopfzeile" :class="{ 'fixed-top': fixHeader }" v-if="saveFailed">
                Beim Speichern ist ein Fehler aufgetreten
            </div>
        </div>
        <!-- Editor für Templates -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- Stelle Variablen für die Templates zur Verfügung -->
                    <div class="card mb-5">
                        <div class="card-header">
                            <a href="#" @click.prevent="toggle('variables')">Variablen</a>
                        </div>
                        <div class="card-body border-bottom" v-show="cards.variables.shown">
                            <p>
                                Um eine Variable zu verwenden, geben Sie in einer Vorlage <i>##(variableName)</i> ein.<br/>
                                Um einen Standardwert einzugeben, der von einer konkreten Bewerbung abhängt, geben Sie
                                im Feld Standardwert <i>##(property)</i> ein, wobei <i>property</i> ein Feld
                                eines Application-Objekts ist. (Bsp. Firmenname: <i>##(company.name)</i>)
                            </p>
                        </div>
                        <div class="card-body" v-show="cards.variables.shown">
                            <div class="my-2 d-flex justify-content-between" v-for="(v, i) in variables">
                                <div style="width: 40%;">
                                    <label class="d-inline-block">Variablenname</label>
                                    <span class="form-control disabled d-block">{{ v.name }}</span>
                                </div>
                                <div style="width: 40%;">
                                    <label :for="'variableStd' + i">Standardwert</label>
                                    <input :id="'variableStd' + i" type="text" class="form-control" v-model="v.standard"/>
                                </div>
                                <div style="width: 10%;">
                                    <a href="#" class="btn text-danger btn-link col-1"
                                       style="font-size: 1.2rem" @click.prevent="removeVariable(i)">&#128465;</a>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <div style="width: 40%">
                                    <label for="variableName">Variablenname</label>
                                    <input type="text" class="form-control" id="variableName"/>
                                    <span class="invalid-feedback d-block" v-show="variableError" :key="variableError" role="alert">
                                        <strong>{{ variableError }}</strong>
                                    </span>
                                </div>
                                <div style="width: 40%">
                                    <label class="d-block invisible">test</label>
                                    <button class="btn btn-outline-primary" @click.prevent="addVariable()">Variable hinzufügen</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bearbeite Vorlagen für individuelle Abschnitte -->
                    <div class="card" v-for="temp in orderedTpls" :key="temp['changed'] + temp['name'] + '_card'">
                        <div class="card-header">
                            <div class="row">
                                <a href="#" class="col-11" @click.prevent="toggle(temp['name'])"><h5>Abschnitt {{ temp['name'] }}</h5></a>
                                <!-- Link zum Löschen eines Abschnitts -->
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

                            <!-- fixer Text, in dem nur einige Schlüsselworte ausgewählt werden. -->
                            <div v-if="temp['choose_keywords']">
                                <label :for="'full_text_' + temp['name']">Vorgeschriebener Text:</label>
                                <textarea :id="'full_text_' + temp['name']" class="form-control border-0"
                                          @input="setKwText($event, temp)">{{ getKwText(temp) }}</textarea>
                                <b>Schlüsselworte:</b>
                                <div class="card mb-1"
                                     v-for="(help, index) in temp['keywords']" :key="help['changed'] + '_kw_' + temp['name'] + index">
                                    <div class="card-header">
                                        <div class="d-flex">
                                            <a href="#" @click.prevent="toggleKw(temp['name'], help['number'])" style="width: 95%" class="d-inline">
                                                Schlüsselworte {{ help['number'] }}
                                            </a>
                                            <a href="#" class="btn text-danger btn-link d-inline" style="font-size: 1.2rem"
                                               @click.prevent="removeKwCat(temp, index)">&#128465;</a>
                                        </div>
                                        <label class="ml-2" :for="'number_' + temp['name'] + '_kw_' + index">Reihenfolge:</label>
                                        <input type="number" class="border-0" style="width:3em" min="0" @change="changeKwCatNumber($event, temp, help)"
                                               :max="temp['keywords'].length - 1" :id="'number_' + temp['name'] + '_kw_' + index" :value="help['number']"/>
                                    </div>
                                    <!-- Zunächst die Überschrift der Kategorie an Schlüsselworten in dem Editor -->
                                    <div class="card-body border-bottom" v-show="cards[temp['name']]['kw' + help['number']]">
                                        <label :for="temp['name'] + '_kw_heading' + index">Überschrift:</label>
                                        <input type="text" :id="temp['name'] + '_kw_heading' + index" class="form-control border-0" v-model="help['heading']"/>
                                    </div>
                                    <!-- Die Konjunktion, die aufezählte Schlüsselworte verbindet -->
                                    <div class="card-body border-bottom" v-show="cards[temp['name']]['kw' + help['number']]">
                                        <label class="ml-2" :for="'conj_' + temp['name'] + '_kw_' + index">Konjunktion:</label>
                                        <input type="text" style="border: 1px solid #ced4da; border-radius: 0.25rem;" maxlength="10" size="10"
                                               :id="'conj_' + temp['name'] + '_kw_' + index" v-model="help['conjunction']"/>
                                    </div>
                                    <!-- Die auswählbaren Schlüsselworte -->
                                    <div class="card-body border-bottom" v-show="cards[temp['name']]['kw' + help['number']]">
                                        <ul>
                                            <li v-for="(kw, ind) in help['tpls']" :key="ind">
                                                <input type="text" class="form-control border-0 px-0 d-inline" style="width: 95%" v-model="help['tpls'][ind]"/>
                                                <a href="#" class="btn text-danger btn-link d-inline" style="font-size: 1.2rem" @click.prevent="removeKw(help, ind)">&#128465;</a>
                                            </li>
                                            <li>
                                                <a href="#" @click.prevent="addKw(help)">Neues Schlüsselwort</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <a href="#" @click.prevent="addKwCat(temp)">Neue Schlüsselwortkategorie</a>
                            </div>
                            <!-- Der gesamte Text ist variabel. -->
                            <div v-else>
                                <label :for="'heading_' + temp['name']">Überschrift:</label>
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

                    <!-- Formular, um Abschnitte hinzuzufügen -->
                    <div class="card">
                        <div class="card-header">
                            <a href="#" @click.prevent="toggle('addSection')"><h5>Abschnitt hinzufügen</h5></a>
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
                                <label class="custom-control-label" for="addSectionKeyword">Schlüsselwortabschnitt</label>
                            </div>
                            <div class="form-group text-right">
                                <a href="#" class="btn btn-small btn-outline-primary" @click.prevent="addSection()">Abschnitt hinzufügen</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary float-right mx-3" @click.prevent="save()">Speichern</button>
                    <button type="button" class="btn btn-outline-primary float-right mx-3" @click.prevent="restoreDefault()">Standard wiederherstellen</button>
                    <a :href="version_route" class="btn btn-secondary float-right mx-3">Versionen verwalten</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
	props: ["save_route", "restore_default_route", "version_route"],

	data() {
		return {
			templates: [],
            cards: {
			    variables: {shown: false},
                addSection: {shown: false}
            },
            variables: [],
            recentlySaved: false,
            saveFailed: false,
            nameError: "",
            variableError: "",
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            fixHeader: false
		};
    },

    computed: {
        orderedTpls: function () {
            //return _.orderBy(this.templates, 'number');
            //_: lodash: Javascript utility library
            let res = _.orderBy(this.templates, 'number');
            res.forEach(tpl => {
                tpl.keywords = _.orderBy(tpl.keywords, 'number');
            });

            return res;
        }
    },

    created() {
        //lodash throttle: Methode wird nicht öfter als alle 100ms aufgerufen
        //Wenn sie davor aufgerufen wird, wird das Ergebnis nicht neu berechnet
        this.handleThrottledScroll = _.throttle(this.handleScroll, 100);
        window.addEventListener('scroll', this.handleThrottledScroll);
    },

    mounted() {
		//Lese zunächst die Templates aus der Datenbank aus
		axios.get(`/bewerbungen/applications/templatesNew`)
            .then(response => response.data).then(data => {
                this.templates = data;
                this.templates.forEach((tpl) => {
                    tpl['changed'] = 0;
                    this.cards[tpl['name']] = {
                        shown: false
                    };
                    tpl['keywords'].forEach((kw) => {
                        kw['changed'] = 0;
                        this.cards[tpl['name']]['kw' + kw['number']] = false;
                    });
                });
            });
		//Lese nun die Variablen aus der Datenbank aus
        axios.get(`/bewerbungen/applications/variables`)
            .then(response => response.data).then(data => {
            this.variables = data;
        });
    },

    methods: {
        toggle(name) {
            this.cards[name].shown = !this.cards[name].shown;
            //TODO ohne forceUpdate zum Laufen bringen
            this.$forceUpdate();
        },
        toggleKw(name, number) {
            this.cards[name]['kw' + number] = !this.cards[name]['kw' + number];
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
        },

        //Füge einem Abschnitt eine neue Vorlage hinzu
        addTpl(tpl) {
            tpl.tpls.push("Bitte geben Sie eine neue Vorlage ein.");
            tpl.changed ++;
        },

        //Entferne eine Vorlage für einen Abschnitt
        removeTpl(tpl, i) {
            tpl.tpls.splice(i, 1);
            tpl.changed ++;
        },

        //Füge einem Abschnitt eine neue Schlüsselwortkategorie hinzu
        addKwCat(tpl) {
            if (tpl.choose_keywords) {
                let cat = {
                    "number": tpl.keywords.length,
                    "heading": "Bitte geben Sie eine Überschrift ein.",
                    "conjunction": "und",
                    "tpls": [
                        "Bitte geben Sie ein Schlüsselwort ein."
                    ]
                };
                tpl.keywords.push(cat);
                tpl.changed ++;
            }
        },

        //Entferne eine Schlüsselwortkategorie aus einem Abschnitt
        removeKwCat(tpl, i) {
            if (tpl.choose_keywords) {
                tpl.keywords.splice(i, 1);
                tpl.changed ++;
            }
        },

        //Füge den auswählbaren Schlüsselworten in einer Kategorie eines Abschnitts ein neues Schlüsselwort hinzu
        addKw(cat) {
            cat.tpls.push("Bitte geben Sie ein Schlüsselwort ein.");
            cat.changed ++;
        },

        //Entferne ein Schlüsselwort aus einer Kategorie
        removeKw(cat, i) {
            cat.tpls.splice(i, 1);
            cat.changed ++;
        },

        //Füge der Vorlage einen neuen Abschnitt hinzu
        addSection() {
            this.nameError = "";

            let kw = document.getElementById('addSectionKeyword').checked;
            let name = $('#addSectionName').val();
            let temp;

            //Stelle sicher, dass der Abschnitt nicht  "attachments" heißt
            if(name === "attachments") {
                this.nameError = 'Der Name "attachments" darf nicht verwendet werden.';
                return;
            }

            //name validieren
            let invalid = !name || !name.trim();
            if (invalid) {
                this.nameError = "Bitte geben Sie einen Namen für den Abschnitt ein.";
                return;
            }
            this.templates.forEach(tpl => {
                invalid = invalid || tpl.name === name;
            });
            if (invalid) {
                this.nameError = "Der eingegebene Name ist der Name eines bereits vorhandenen Abschnitts.";
                return;
            }

            //Erstelle einen neuen Schlüsselwortabschnitt
            if (kw) {
                temp = {
                    changed: 0,
                    heading: null,
                    is_heading: false,
                    fix: false,
                    choose_keywords: true,
                    tpls: [
                        "Bitte geben Sie einen Text ein. (Platzhalter für Schlüsselworte: ",
                        ")"
                    ],
                    keywords: [
                        {
                            changed: 0,
                            heading: "Bitte fügen Sie eine Überschrift hinzu.",
                            conjunction: "und",
                            number: 0,
                            tpls: [
                                "Bitte fügen Sie ein Schlüsselwort hinzu."
                            ]
                        }
                    ]
                };
            }
            //Erstelle einen neuen Standardabschnitt
            else {
                temp = {
                    heading: "Bitte geben Sie eine Überschrift an.",
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

        addVariable() {
            let app = this;
            let name = document.getElementById('variableName').value;
            this.variableError = "";

            if (!name || !name.trim()) {
                this.variableError = "Bitte geben Sie einen eindeutigen Vairablennamen ein.";
            }
            this.variables.forEach(function(v) {
                if (name === v.name) {
                    app.variableError = "Es existiert bereits eine Variable mit dem eingegebenen Namen.";
                }
            });
            if (this.variableError) {
                return;
            }

            this.variables.push({
                name: name,
                standard: ""
            });
        },

        removeVariable(i) {
            this.variables.splice(i, 1);
        },

        //Ändere die Reihenfolge der Abschnitte (Verschiebe einen Abschnitt und passe die Nummer der restlichen Abschnitte an)
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
                            this.cards[temp.name].shown = false;
                        }
                    });
                }
                //nach oben verschieben
                if (newVal < old) {
                    this.templates.forEach(temp => {
                        if (temp.number < old && temp.number >= newVal) {
                            temp.number++;
                            temp.changed++;
                            this.cards[temp.name].shown = false;
                        }
                    });
                }

                this.cards[tpl.name].shown = false;
                tpl.number = newVal;
                tpl.changed++;
            }
        },


        /**
         * Ändere die Reihenfolge der Schlüsselwortkategorien in einem Abschnitt
         * tpl: Der Abschnitt
         * cat: Die Kategorie, die verschoben wird
         */
        changeKwCatNumber(e, tpl, cat) {
            let old = cat.number;
            let newVal = e.target.value;
            this.cards[tpl.name]['kw' + old] = false;
            if (newVal >= 0 && newVal < tpl.keywords.length) {
                //nach unten verschieben
                if (newVal > old) {
                    tpl.keywords.forEach(help => {
                        if (help.number > old && help.number <= newVal) {
                            this.cards[tpl.name]['kw' + help.number] = false;
                            help.number--;
                        }
                    });
                }
                //nach oben verschieben
                if (newVal < old) {
                    tpl.keywords.forEach(help => {
                        if (help.number < old && help.number >= newVal) {
                            this.cards[tpl.name]['kw' + help.number] = false;
                            help.number++;
                        }
                    });
                }

                cat.number = newVal;
                tpl.changed++;
            }
        },

        //Speichere die Änderungen an der Vorlage
        save() {
            let route = this.save_route;

            axios.post(route, {templates: this.templates, variables: this.variables, _method: "patch"})
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
                                shown: false
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
        },

        handleScroll(e) {
            if ($('.kopfzeile').length) {
                this.fixHeader = window.pageYOffset > $('#parent_header').offset().top;
            }
        }
    }
};
</script>
