<!-- TODO aufräumen -->

<template>
    <div class="row">
        <!--<div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <b>Vorschau</b>
                </div>
                <div class="card-body" style="max-height: 100vh; overflow-y: scroll;">
                    <p>{{ preview.greeting }}</p>

                    <p v-for="(temp, key) in variableTemplates">
                        {{ preview[key] }}
                    </p>

                    <p>{{ preview.ending }}</p>

                    <p>Mit freundlichen Grüßen</p>

                    <p class="mt-5">Max Mustermann, {{ currentDate }}</p>

                    <hr>
                </div>
            </div>
        </div>-->

        <!-- Editor -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="max-height: 76vh; overflow-y: scroll;">
                    <!-- Bearbeite Textbausteine für die Anrede -->
                    <div class="card">
                        <div class="card-header">
                            <a href="#" @click.prevent="toggle(cards.greeting)"><h5>{{ tpl.greeting.heading }}</h5></a>
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
                        </div>
                        <div class="card-body" v-if="cards[key].shown">
                            <!-- fixer Text, in dem nur einige Schlüsselworte ausgewählt werden. -->
                            <div v-if="tpl[key].chooseKeywords">
                                <label :for="'full_text_' + key">Vorgeschreibener Text:</label>
                                <textarea :id="'full_text_' + key" class="form-control border-0" v-model="keywordTpls()[key]"/>
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
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["tpl",],

    data() {
        return {
            /*preview: {
                //greeting: this.tpl.greeting.tpls[0]
            },*/
            templates: {
                //greeting: []
            },
            cards: {
                addSection: {shown: false}
            }
        }
    },

    created() {
        //Erstelle ein Objekt, das aus Arrays besteht in templates
        //leichter, über Arrays zu iterieren
        Object.keys(this.tpl).forEach(key => {
            this.templates[key] = Object.values(this.tpl[key]);
            //this.preview[key] = this.tpl[key].tpls[0];
            this.cards[key] = {shown: false};
        });
    },

    computed: {
        /*currentDate() {
            let date = new Date();
            let day = ("0" + date.getDate()).slice(-2);
            let month = ("0" + (date.getMonth() + 1)).slice(-2);
            let year = date.getFullYear();

            let dateString = `${day}.${month}.${year}`;

            return dateString;
        },*/

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
                        "Bitte geben Sie einen Text ein. (Platzhalter für Schlüsselworte: ###)"
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
            this.tpl[name] = temp;
            this.cards[name] = {shown: false};
            this.$forceUpdate();
        },
        removeAbschnitt(key) {
            delete this.tpl[key];
            this.$forceUpdate();
        },
        addAnrede() {
            this.tpl.greeting.tpls.push("anrede");
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
        }
    }
}
</script>
