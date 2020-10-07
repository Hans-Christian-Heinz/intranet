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
                    <p>{{ data.greeting.body }}</p>

                    <p>{{ data.awareofyou.body }}</p>

                    <p>{{ data.currentactivity.body }}</p>

                    <p>{{ data.whycontact.body }}</p>

                    <p>{{ workAndSkillBody }}</p>

                    <p>{{ data.ending.body }}</p>

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
                    <div class="form-group">
                        <h5>Anrede</h5>

                        <div class="input-group">
                            <input type="text" class="form-control" v-model="data.greeting.body">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" v-for="tpl in templates.greeting"
                                       @click.prevent="data.greeting.body = tpl">{{ tpl }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <h5>Wie bist du auf diese Stelle aufmerksam geworden?</h5>
                        <div class="input-group">
                            <textarea class="form-control" rows="4" v-model="data.awareofyou.body"></textarea>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" v-for="tpl in templates.awareofyou"
                                       @click.prevent="data.awareofyou.body = tpl">{{ tpl }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <h5>Was ist deine derzeitige Beschäftigung?</h5>
                        <div class="input-group">
                            <textarea class="form-control" rows="4" v-model="data.currentactivity.body"></textarea>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" v-for="tpl in templates.currentactivity"
                                       @click.prevent="data.currentactivity.body = tpl">{{ tpl }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <h5>Warum bewirbst du dich bei dem Unternehmen?</h5>
                        <div class="input-group">
                            <textarea class="form-control" rows="4" v-model="data.whycontact.body"></textarea>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" v-for="tpl in templates.whycontact"
                                       @click.prevent="data.whycontact.body = tpl">{{ tpl }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <h5>Wie zeichnet sich deine Arbeitsweise aus?</h5>
                        <div>
                            <input type="checkbox" id="zuverlässig" value="zuverlässig" v-model="data.wayOfWork">
                            <label for="zuverlässig">Zuverlässig</label>
                        </div>

                        <div>
                            <input type="checkbox" id="verantwortungsbewusst" value="verantwortungsbewusst" v-model="data.wayOfWork">
                            <label for="verantwortungsbewusst">Verantwortungsbewusst</label>
                        </div>

                        <div>
                            <input type="checkbox" id="präzise" value="präzise" v-model="data.wayOfWork">
                            <label for="präzise">Präzise</label>
                        </div>

                        <div>
                            <input type="checkbox" id="engagiert" value="engagiert" v-model="data.wayOfWork">
                            <label for="engagiert">Engagiert</label>
                        </div>

                        <div>
                            <input type="checkbox" id="gewissenhaft" value="gewissenhaft" v-model="data.wayOfWork">
                            <label for="gewissenhaft">Gewissenhaft</label>
                        </div>

                        <div>
                            <input type="checkbox" id="ausdauernd" value="ausdauernd" v-model="data.wayOfWork">
                            <label for="ausdauernd">Ausdauernd</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <h5>Welche Begriffe beschreiben am besten deine persönlichen Kompetenzen?</h5>
                        <div>
                            <input type="checkbox" id="flexibel" value="flexibel" v-model="data.skills">
                            <label for="flexibel">Flexibel</label>
                        </div>

                        <div>
                            <input type="checkbox" id="motiviert" value="motiviert" v-model="data.skills">
                            <label for="motiviert">Motiviert</label>
                        </div>

                        <div>
                            <input type="checkbox" id="teamorientiert" value="teamorientiert" v-model="data.skills">
                            <label for="teamorientiert">Teamorientiert</label>
                        </div>

                        <div>
                            <input type="checkbox" id="belastbar" value="belastbar" v-model="data.skills">
                            <label for="belastbar">Belastbar</label>
                        </div>

                        <div>
                            <input type="checkbox" id="selbstständig" value="selbstständig" v-model="data.skills">
                            <label for="selbstständig">Selbstständig</label>
                        </div>

                        <div>
                            <input type="checkbox" id="aufgeschlossen" value="aufgeschlossen" v-model="data.skills">
                            <label for="aufgeschlossen">Aufgeschlossen</label>
                        </div>

                        <div>
                            <input type="checkbox" id="begeisterungsfähig" value="begeisterungsfähig" v-model="data.skills">
                            <label for="begeisterungsfähig">Begeisterungsfähig</label>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <h5>Schlusswort</h5>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" v-for="tpl in templates.whycontact"
                               @click.prevent="data.whycontact.body = tpl">{{ tpl }}</a>
                        </div>
                        <div v-for="(tpl, index) in templates.ending">
                            <input type="radio" :id="'version' + index" :value="tpl" v-model="data.ending.body">
                            <label :for="'version' + index">Version {{ index}}</label>
                        </div>
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
            data: {
                greeting: {
                    body: ""
                },

                awareofyou: {
                    body: ""
                },

                currentactivity: {
                    body: ""
                },

                whycontact: {
                    body: ""
                },

                wayOfWork: ["zuverlässig", "verantwortungsbewusst", "präzise"],
                skills: ["flexibel", "motiviert", "teamorientiert"],

                ending: {
                    body: ""
                }
            },

            templates: {},

            recentlySaved: false
        };
    },

    mounted() {
        //Lese zunächst die Templates aus
        axios.get(`/bewerbungen/applications/templates`)
            .then(response => response.data).then(data => {
                Object.keys(data).forEach(key => {
                    this.templates[key] = data[key].tpls;
                });

                if (!this.data.greeting.body.length) this.data.greeting.body = this.templates.greeting[0];
                if (!this.data.awareofyou.body.length) this.data.awareofyou.body = this.templates.awareofyou[0];
                if (!this.data.currentactivity.body.length) this.data.currentactivity.body = this.templates.currentactivity[0];
                if (!this.data.whycontact.body.length) this.data.whycontact.body = this.templates.whycontact[0];
                if (!this.data.ending.body.length) this.data.ending.body = this.templates.ending[0];

                /*if (!this.data.greeting.body.length) this.data.greeting.body = this.data.greeting.templates.first;
                if (!this.data.awareofyou.body.length) this.data.awareofyou.body = this.data.awareofyou.templates.first;
                if (!this.data.currentactivity.body.length) this.data.currentactivity.body = this.data.currentactivity.templates.first;
                if (!this.data.whycontact.body.length) this.data.whycontact.body = this.data.whycontact.templates.first;
                if (!this.data.ending.body.length) this.data.ending.body = this.data.ending.templates.first;*/

                if (this.saved.greeting.body.length) this.data.greeting.body = this.saved.greeting.body;
                if (this.saved.awareofyou.body.length) this.data.awareofyou.body = this.saved.awareofyou.body;
                if (this.saved.currentactivity.body.length) this.data.currentactivity.body = this.saved.currentactivity.body;
                if (this.saved.whycontact.body.length) this.data.whycontact.body = this.saved.whycontact.body;
                if (this.saved.wayOfWork.length) this.data.wayOfWork = this.saved.wayOfWork;
                if (this.saved.skills.length) this.data.skills = this.saved.skills;
                if (this.saved.ending.body.length) this.data.ending.body = this.saved.ending.body;
            });
    },

    computed: {
        workAndSkillBody() {
            let wayOfWork = [...this.data.wayOfWork];
            let lastWayOfWork = this.data.wayOfWork[this.data.wayOfWork.length - 1];
            let skills = [...this.data.skills];
            let lastSkill = this.data.skills[this.data.skills.length - 1];

            if (wayOfWork.length > 1) {
                wayOfWork.pop();
            }

            if (skills.length > 1) {
                skills.pop();
            }

            let wayOfWorkMessage = (this.data.wayOfWork.length > 1) ? wayOfWork.join(", ") + " und " + lastWayOfWork : lastWayOfWork;
            let skillsMessage = (this.data.skills.length > 1) ? skills.join(", ") + " und " + lastSkill : lastSkill;

            return `In eine neue Aufgabe bei Ihnen kann ich verschiedene Stärken einbringen. So bin ich meine Aufgaben sehr ${wayOfWorkMessage} angegangen. Mit mir gewinnt Ihr Unternehmen einen Mitarbeiter, der ${skillsMessage} ist. Außerdem habe ich in früheren Projekten insbesondere ausgeprägte Kommunikationsstärke, hohe Lernbereitschaft und viel Kreativität unter Beweis stellen können.`;
        },

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
        }
    }
}
</script>
