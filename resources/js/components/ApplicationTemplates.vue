<!-- TODO aufräumen -->

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
        <!-- Editor -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="max-height: 76vh; overflow-y: scroll;">
                    <!-- Bearbeite Textbausteine für die Anrede -->
                    <div class="card">
                        <div class="card-header">
                            <a href="#" @click.prevent="toggle(cards.greeting)"><h5>{{ tpl.greeting.heading }}</h5></a>
                            <label for="number_greeting">Reihenfolge</label>
                            <input type="number" id="number_greeting" class="border-0" disabled v-model="tpl.greeting.number"/>
                        </div>
                        <div class="card-body" v-if="cards.greeting.shown">
                            <p>Templates:</p>
                            <ul>
                                <li v-for="(greeting, index) in tpl.greeting.tpls" :key="index" class="row">
                                    <input type="text" :name="'greeting' + index" :id="'greeting' + index" class="form-control border-0 col-11"
                                           v-model="tpl.greeting.tpls[index]"/>
                                    <a href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem" @click.prevent="removeTpl('greeting', index)">&#128465</a>
                                </li>
                                <li>
                                    <a href="#" @click.prevent="addTpl('greeting')">Neue Anrede</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Bearbeite Textbausteine für die variablen Kategorien -->
                    <div class="card" v-for="(temp, key) in variableTpls()">
                        <div class="card-header">
                            <div class="row">
                                <a href="#" class="col-11" @click.prevent="toggle(cards[key])"><h5>Abschnitt {{ key }}</h5></a>
                                <!-- Link zum Löschen eines Abschnitts -->
                                <a href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem" @click.prevent="removeAbschnitt(key)">&#128465</a>
                            </div>
                            <label :for="'number_' + key">Reihenfolge:</label>
                            <input type="number" class="border-0" style="width:3em" min="1" @change="changeNumber(key)"
                                   :max="tpl.ending.number - 1" :id="'number_' + key" v-model="tpl[key].number"/>
                        </div>
                        <div class="card-body" v-if="cards[key].shown">
                            <!-- fixer Text, in dem nur einige Schlüsselworte ausgewählt werden. -->
                            <div v-if="tpl[key].chooseKeywords">
                                <label :for="'full_text_' + key">Vorgeschreibener Text:</label>
                                <textarea :id="'full_text_' + key" class="form-control border-0" @input="changeKwText($event, key)">{{ keywordTpls()[key] }}</textarea>
                                <p>Schlüsselworte:</p>
                                <div style="border: solid lightgrey 1px" class="mb-1" v-for="(help, index) in tpl[key].keywords">
                                    <div class="row">
                                        <label :for="'kw_heading' + index" class="col-11">Überschrift:</label>
                                        <a href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem"
                                           @click.prevent="removeKeywordCategory(key, index)">&#128465</a>
                                    </div>
                                    <input type="text" :id="'kw_heading' + index" class="form-control border-0" v-model="tpl[key].keywords[index].heading"/>
                                    <hr>
                                    <ul>
                                        <li v-for="(kw, ind) in tpl[key].keywords[index].tpls" class="row">
                                            <input type="text" class="form-control border-0 col-11" v-model="tpl[key].keywords[index].tpls[ind]"/>
                                            <a href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem"
                                               @click.prevent="removeKeyword(key, index, ind)">&#128465</a>
                                        </li>
                                        <li>
                                            <a href="#" @click.prevent="addKeyword(key, index)">Neues Schlüsselwort</a>
                                        </li>
                                    </ul>
                                </div>
                                <a href="#" @click.prevent="addKeywordCategory(key)">Neue Schlüsselwortkategorie</a>
                            </div>

                            <!-- Der gesamte Text ist variabel. -->
                            <div v-else>
                                <label :for="'heading_' + key">Überschrift:</label>
                                <input type="text" :id="'heading_' + key" class="form-control border-0" v-model="tpl[key].heading"/>

                                <p>Templates:</p>
                                <ul>
                                    <li v-for="(help, index) in temp.tpls" class="row">
                                        <textarea :id="key+ '_tpl_' + index" class="form-control border-0 col-11" v-model="tpl[key].tpls[index]"/>
                                        <a href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem" @click.prevent="removeTpl(key, index)">&#128465</a>
                                    </li>
                                    <li>
                                        <a href="#" @click.prevent="addTpl(key)">Neues Template</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Bearbeite Textbausteine für das Schlusswort -->
                    <div class="card">
                        <div class="card-header">
                            <a href="#" @click.prevent="toggle(cards.ending)"><h5>{{ tpl.ending.heading }}</h5></a>
                            <label for="number_ending">Reihenfolge:</label>
                            <input type="number" disabled class="border-0" id="number_ending" v-model="tpl.ending.number"/>
                        </div>
                        <div class="card-body" v-if="cards.ending.shown">
                            <p>Templates</p>
                            <ul>
                                <li v-for="(ending, index) in tpl.ending.tpls" :key="index" class="row">
                                    <textarea type="text" :name="'ending' + index" :id="'ending' + index" class="form-control border-0 col-11"
                                           v-model="tpl.ending.tpls[index]"/>
                                    <a href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem" @click.prevent="removeTpl('ending', index)">&#128465</a>
                                </li>
                                <li>
                                    <a href="#" @click.prevent="addTpl('ending')">Neues Schlusswort</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Link, um Abschnitte hinzuzufügen -->
                    <div class="card">
                        <div class="card-header">
                            <a href="#" @click.prevent="toggle(cards.addSection)"><h5>Abschnitt hinzufügen</h5></a>
                        </div>
                        <div class="card-body" v-if="cards.addSection.shown">
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
                                <a href="#" class="btn btn-small btn-outline-primary" @click.prevent="addAbschnitt()">Abschnitt hinzufügen</a>
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
    props: ["tpl", "save_route", "restore_default_route"],

    data() {
        return {
            templates: {
                //greeting: []
            },
            cards: {
                addSection: {shown: false}
            },
            recentlySaved: false,
            saveFailed: false
        }
    },

    created() {
        //Erstelle ein Objekt, das aus Arrays besteht in templates
        //leichter, über Arrays zu iterieren
        Object.keys(this.tpl).forEach((key, i) => {
            //Die Reihenfolge, in der die Abschnitte ausgelesen werden
            //this.tpl[key].number = i;
            this.templates[key] = Object.values(this.tpl[key]);
            //Nummeriere auch hier, um beim Ändern der Reihenfolge Zugriff auf die alte Nummer zu haben
            this.cards[key] = {
                shown: false,
                //number: i
                number: this.tpl[key].number
            };
        });
    },

    computed: {
        variableTemplates: function() {
            let filtered = {};
            Object.keys(this.tpl).forEach(key => {
                if (!this.tpl[key].fix) {
                    filtered[key] = this.tpl[key];
                }
            });

            return filtered;
            /*return this.tpl.filter(function (tpl) {
                return ! tpl.fix;
            });*/
        },

        keywordTemplates: function() {
            let kwTpl = {};
            Object.keys(this.tpl).forEach(key => {
                if (this.tpl[key].chooseKeywords) {
                    kwTpl[key] = this.tpl[key].text.join('###');
                }
            });

            return kwTpl;
        }
    },

    methods: {
        /*greetingPreview(event) {
            this.preview.greeting = event.target.value;
            this.$forceUpdate();
        },*/
        variableTpls() {
            let filtered = {};
            Object.keys(this.tpl).forEach(key => {
                if (!this.tpl[key].fix) {
                    filtered[key] = this.tpl[key];
                }
            });

            return filtered;
        },
        keywordTpls() {
            let kwTpl = {};
            Object.keys(this.tpl).forEach(key => {
                if (this.tpl[key].chooseKeywords) {
                    kwTpl[key] = this.tpl[key].text.join('###');
                }
            });

            return kwTpl;
        },

        //TODO find alternative to forceUpdate
        //TODO validate section-name
        addAbschnitt() {
            let kw = document.getElementById('addSectionKeyword').checked;
            let name = $('#addSectionName').val();
            let temp;
            if (kw) {
                temp = {
                    fix: false,
                    chooseKeywords: true,
                    text: [
                        "Bitte geben Sie einen Text ein. (Platzhalter für Schlüsselworte:) ",
                        " "
                    ],
                    keywords: [
                        {
                            heading: "Bitte fügen Sie eine Überschrift hinzu.",
                            conjunction: "und",
                            tpls: [
                                "Bitte fügen Sie ein Schlüsselwort hinzu."
                            ]
                        }
                    ]
                };
            }
            else {
                temp = {
                    heading: "Bitte geben Sie eine Überschrift an.",
                    fix: false,
                    tpls: [
                        "Bitte geben Sie ein Template an."
                    ]
                };
            }
            temp.number = this.tpl.ending.number;
            this.tpl.ending.number ++;
            this.tpl[name] = temp;
            this.cards[name] = {shown: false};
            this.$forceUpdate();
        },
        removeAbschnitt(key) {
            delete this.tpl[key];
            this.$forceUpdate();
        },
        addTpl(key) {
            this.tpl[key].tpls.push("Bitte geben Sie ein Template an.");
            this.$forceUpdate();
        },
        removeTpl(key, index) {
            this.tpl[key].tpls.splice(index, 1);
            this.$forceUpdate();
        },
        addKeyword(key, index) {
            this.tpl[key].keywords[index].tpls.push("Bitte fügen Sie ein Schlüsselwort hinzu.");
            this.$forceUpdate();
        },
        removeKeyword(key, index, ind) {
            this.tpl[key].keywords[index].tpls.splice(ind, 1);
            this.$forceUpdate();
        },
        addKeywordCategory(key) {
            this.tpl[key].keywords.push({
                "heading": "Bitte fügen Sie eine Überschrift hinzu.",
                "conjunction": "und",
                "tpls": [
                    "Bitte fügen Sie ein Schlüsselwort hinzu."
                ]
            });
            this.$forceUpdate();
        },
        removeKeywordCategory(key, index) {
            this.tpl[key].keywords.splice(index, 1);
            this.$forceUpdate();
        },
        toggle(card) {
            card.shown = !card.shown;
            this.$forceUpdate();
        },
        changeNumber(key) {
            //Passe die Nummer bzw. Reihenfolge aller variablen Abschnitte an
            let number = this.tpl[key].number;
            let old_number = this.cards[key].number;
            Object.keys(this.tpl).forEach(i_key => {
                if (key !== i_key) {
                    let i_number = this.tpl[i_key].number;
                    //Zwei Fälle: old_number > number und old_number < number
                    if (old_number > number) {
                        if (i_number >= number && i_number < old_number) {
                            this.tpl[i_key].number ++;
                            this.cards[i_key].number ++;
                        }
                    }
                    if (old_number < number) {
                        if (i_number <= number && i_number > old_number) {
                            this.tpl[i_key].number --;
                            this.cards[i_key].number --;
                        }
                    }
                }
            });
            this.cards[key].number = number;

            this.$forceUpdate();
        },

        changeKwText(e, key) {
            let text = e.target.value;
            let temp = this.tpl[key];
            this.tpl[key].text = text.split("###");
        },

        save() {
            let route = this.save_route;

            axios.post(route, {tpl: this.tpl, _method: "patch"})
                .then(response => response.data)
                .then(data => {
                    // do something
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

                        this.tpl = data;

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
}
</script>
