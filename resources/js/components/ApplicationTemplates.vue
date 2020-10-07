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
                                <li v-for="(greeting, index) in tpl.greeting.tpls" :key="index">
                                    <input type="text" :name="'greeting' + index" :id="'greeting' + index" class="form-control border-0"
                                           v-model="tpl.greeting.tpls[index]"/>
                                </li>
                                <li>
                                    <a href="#" @click.prevent="addTpl('greeting')">Neue Anrede</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Bearbeite Textbausteine für die variablen Kategorien -->
                    <div class="card" v-for="(temp, key) in variableTemplates">
                        <div class="card-header">
                            <a href="#" @click.prevent="toggle(cards[key])"><h5>Abschnitt {{ key }}</h5></a>
                        </div>
                        <div class="card-body" v-if="cards[key].shown">
                            <!-- fixer Text, in dem nur einige Schlüsselworte ausgewählt werden. -->
                            <div v-if="tpl[key].chooseKeywords">
                                <label :for="'full_text_' + key">Vorgeschreibener Text:</label>
                                <textarea :id="'full_text_' + key" class="form-control border-0" v-model="keywordTemplates[key]"/>
                                <p>Schlüsselworte:</p>
                                <div class="form-group" v-for="(help, index) in tpl[key].keywords">
                                    <label :for="'kw_heading' + index">Überschrift:</label>
                                    <input type="text" :id="'kw_heading' + index" class="form-control border-0" v-model="tpl[key].keywords[index].heading"/>
                                    <ul>
                                        <li v-for="(kw, ind) in tpl[key].keywords[index].tpls">
                                            <input type="text" class="form-control border-0" v-model="tpl[key].keywords[index].tpls[ind]"/>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Der gesamte Text ist variabel. -->
                            <div v-else>
                                <label :for="'heading_' + key">Überschrift:</label>
                                <input type="text" :id="'heading_' + key" class="form-control border-0" v-model="tpl[key].heading"/>

                                <p>Templates:</p>
                                <ul>
                                    <li v-for="(help, index) in temp.tpls">
                                        <textarea :id="key+ '_tpl_' + index" class="form-control border-0" v-model="tpl[key].tpls[index]"/>
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
                                <li v-for="(ending, index) in tpl.ending.tpls" :key="index">
                                    <textarea type="text" :name="'ending' + index" :id="'ending' + index" class="form-control border-0"
                                           v-model="tpl.ending.tpls[index]"/>
                                </li>
                                <li>
                                    <a href="#" @click.prevent="addTpl('ending')">Neues Schlusswort</a>
                                </li>
                            </ul>
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
            cards: {}
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
        addAnrede() {
            this.tpl.greeting.tpls.push("anrede");
            this.$forceUpdate();
        },
        addTpl(key) {
            this.tpl[key].tpls.push("");
            this.$forceUpdate();
        },
        toggle(card) {
            card.shown = !card.shown;
            this.$forceUpdate();
        }
    }
}
</script>
