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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" style="max-height: 75vh; overflow-y: scroll;">
                        <p v-for="(text, name) in data" :key="name + text.changed">
                            <b v-if="text.is_heading">
                                <span class="span_edit" :data-key="name" contenteditable>{{ text.text }}</span>
                            </b>
                            <span v-else class="span_edit" :data-key="name" contenteditable>{{ text.text }}</span>
                        </p>

                        <p>Mit freundlichen Grüßen</p>

                        <p>{{ this.user.full_name }}, {{ currentDate }}</p>

                        <p class="mt-2"><b>Anlagen:</b></p>
                        <ul :key="attachments.changed">
                            <li v-for="(att, i) in attachments.values">
                                <input type="text" :id="'att_' + i" class="form-control border-0 px-0 w-75 d-inline" v-model="attachments.values[i]">
                                <a href="#" class="btn text-danger btn-link d-inline" @click.prevent="removeAttachment(i)">&#128465;</a>
                            </li>
                            <li>
                                <a href="#" @click.prevent="addAttachment()">Anlage hinzufügen</a>
                            </li>
                        </ul>
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
    props: ["route", "saved", "user"],

    data () {
        return {
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

    created() {
        //lodash debounce: Methode wird nicht öfter als alle 100ms aufgerufen
        //Wenn sie davor aufgerufen wird, wird das Ergebnis nicht neu berechnet
        this.handleDebouncedScroll = _.debounce(this.handleScroll, 100);
        window.addEventListener('scroll', this.handleDebouncedScroll);
    },

    mounted() {
        this.data = Object.assign({}, this.saved);
        delete this.data.attachments;

        if (this.saved.attachments) {
            this.attachments.values = this.saved.attachments;
        }
        else {
            this.attachments.values = ['Lebenslauf'];
        }
    },

    computed: {
        currentDate() {
            let date = new Date();
            let day = ("0" + date.getDate()).slice(-2);
            let month = ("0" + (date.getMonth() + 1)).slice(-2);
            let year = date.getFullYear();

            return `${day}.${month}.${year}`;
        }
    },

    methods: {
        //Änderungen speichern
        save() {
            const app = this;
            $('span.span_edit').each(function() {
                app.data[$(this).attr('data-key')].text = $(this).html();
            });

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
