<template>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info" v-if="recentlySaved">
                Die Änderungen wurden erfolgreich gespeichert.
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body" style="max-height: 100vh; overflow-y: scroll;">
                    <p v-for="(text, key) in data">
                        <span v-if="! text.keywords">{{ text }}</span>
                        <span v-else>{{ keywordsText(key, data[key].values) }}</span>
                    </p>

                    <p>Mit freundlichen Grüßen</p>

                    <p class="mt-5">{{ this.user.full_name }}, {{ currentDate }}</p>

                    <hr>

                    <button type="button" class="btn btn-outline-primary mt-3" @click="save()">Änderungen speichern</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body" style="max-height: 76vh; overflow-y: scroll;">
                    <div class="form-group" v-for="(tpl, key) in this.templates">
                        <h5>{{ tpl.heading }}</h5>

                        <div class="input-group" v-if="!tpl.checkbox">
                            <input type="text" class="form-control" v-model="data[key]">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" v-for="temp in tpl.tpls" @click.prevent="useTemplate(key, temp, false)">{{ temp }}</a>
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            <div v-for="(temp, index) in tpl.tpls">
                                <input type="checkbox" @change="$forceUpdate()"
                                       :id="key + '_tpl_' + index" :value="temp" v-model="data[tpl.key].values[tpl.index]"/>
                                <label :for="key + '_tpl_' + index">{{ temp }}</label>
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
</template>

<script>
export default {
    props: ["route", "saved", "user"],

    data() {
        return {
            data: {},

            templates: {},
            helpTpls: {},

            recentlySaved: false
        };
    },

    mounted() {
        //Lese zunächst die Templates aus
        axios.get(`/bewerbungen/applications/templates`)
            .then(response => response.data).then(data => {
                Object.keys(data).forEach(key => {
                    //Die templates-Variable soll die im Editor verfügbaren templates beinhalten
                    //Die data-Variable soll die im Dokument bzw. in der Vorschau angezeigten Werte beinhalten
                    if (data[key].chooseKeywords) {
                        this.helpTpls[key] = data[key];

                        let indices = [];
                        for (let i = 0; i < data[key].keywords.length; i++) {
                            indices[i] = [];
                            for (let j = 0; j < Math.min(3, data[key].keywords[i].tpls.length); j++) {
                                indices[i].push(j);
                            }
                            this.templates[key + "_keyword_" + i] = {};
                            this.templates[key + "_keyword_" + i].heading = data[key].keywords[i].heading;
                            this.templates[key + "_keyword_" + i].tpls = data[key].keywords[i].tpls;
                            this.templates[key + "_keyword_" + i].checkbox = true;
                            this.templates[key + "_keyword_" + i].key = key;
                            this.templates[key + "_keyword_" + i].index = i;
                        }

                        let values = this.kwValues(key, indices);
                        if (! this.data[key]) {
                            this.data[key] = {
                                keywords: true,
                                values: values
                            };
                        }
                        if (this.saved[key]) {
                            this.data[key] = this.saved[key];
                        }
                    }
                    else {
                        this.templates[key] = {};
                        this.templates[key].heading = data[key].heading;
                        this.templates[key].tpls = data[key].tpls;

                        if(! (this.data[key] && this.data[key].length)) {
                            this.data[key] = data[key].tpls[0];
                        }
                    }

                    if (this.saved[key] && this.saved[key].length) {
                        this.data[key] = this.saved[key];
                    }
                });

                this.$forceUpdate();
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
        }
    },

    methods: {
        save() {
            let route = this.route;

            axios.post(this.route, {body: this.data, _method: "patch"})
                .then(response => response.data)
                .then(data => {
                    // do something

                    this.recentlySaved = true;

                    setTimeout(() => {
                        this.recentlySaved = false;
                    }, 3000);
                })
                .catch(error => {
                    console.log(error);
                });
        },

        useTemplate(key, temp, keyword) {
            if (! keyword) {
                this.data[key] = temp;
                this.$forceUpdate();
            }
        },

        //key: der Index des beschriebenen Abschnitts
        //indices: mehrdimensionaler Array: [Schlüsselwortkategorie => [Schlüsselwortnr, Schlüsselwortnr...], ...]
        kwValues(key, indices) {
            let template = this.helpTpls[key];
            let res = [];

            for (let i = 0; i < Math.min(indices.length, template.keywords.length); i++) {
                res[i] = [];
                for (let j = 0; j < indices[i].length; j++) {
                    if (indices[i][j] < template.keywords[i].tpls.length) {
                        res[i].push(template.keywords[i].tpls[j]);
                    }
                }
            }

            return res;
        },

        //key: der Index des beschriebenen Abschnitts
        //values: mehrdimensionaler Array: [Schlüsselwortkategorie => [Schlüsselwort, Schlüsselwort...], ...]
        keywordsText(key, values) {
            let template = this.helpTpls[key];
            let res = "";

            //Gehe den Array text im Template durch. (Der Text, in den Schlüsselworte einzusetzen sind, unterbrochen an
            //den Stellen, an denen Schlüsselworte einzusetzen sind.
            for (let i = 0; i < template.text.length; i++) {
                res += template.text[i];
                if (i < values.length) {
                    for (let j = 0; j < values[i].length; j++){
                        res += values[i][j];
                        if (j < values[i].length - 2) {
                            res += ", ";
                        }
                        if (j === values[i].length - 2) {
                            res += " " + template.keywords[i].conjunction + " ";
                        }
                    }
                }
            }

            return res;
        }
    }
}
</script>
