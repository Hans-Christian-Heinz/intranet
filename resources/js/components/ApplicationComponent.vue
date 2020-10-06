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
                                    <a class="dropdown-item" href="#" @click.prevent="data.greeting.body = data.greeting.templates.first">{{ data.greeting.templates.first }}</a>
                                    <a class="dropdown-item" href="#" @click.prevent="data.greeting.body = data.greeting.templates.second">{{ data.greeting.templates.second }}</a>
                                    <a class="dropdown-item" href="#" @click.prevent="data.greeting.body = data.greeting.templates.third">{{ data.greeting.templates.third }}</a>
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
                                    <a class="dropdown-item" href="#" @click.prevent="data.awareofyou.body = data.awareofyou.templates.first">Stellenanzeige</a>
                                    <a class="dropdown-item" href="#" @click.prevent="data.awareofyou.body = data.awareofyou.templates.second">Initiativbewerbung</a>
                                    <a class="dropdown-item" href="#" @click.prevent="data.awareofyou.body = data.awareofyou.templates.third">Vorkontakt</a>
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
                                    <a class="dropdown-item" href="#" @click.prevent="data.currentactivity.body = data.currentactivity.templates.first">Angestellte/r</a>
                                    <a class="dropdown-item" href="#" @click.prevent="data.currentactivity.body = data.currentactivity.templates.second">Studium</a>
                                    <a class="dropdown-item" href="#" @click.prevent="data.currentactivity.body = data.currentactivity.templates.third">Ausbildung/Lehre</a>
                                    <a class="dropdown-item" href="#" @click.prevent="data.currentactivity.body = data.currentactivity.templates.fourth">Praktikum</a>
                                    <a class="dropdown-item" href="#" @click.prevent="data.currentactivity.body = data.currentactivity.templates.fifth">Schule</a>
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
                                    <a class="dropdown-item" href="#" @click.prevent="data.whycontact.body = data.whycontact.templates.first">bessere Entwicklungsmöglichkeiten</a>
                                    <a class="dropdown-item" href="#" @click.prevent="data.whycontact.body = data.whycontact.templates.second">mehr Verantwortung</a>
                                    <a class="dropdown-item" href="#" @click.prevent="data.whycontact.body = data.whycontact.templates.third">beschriebene Aufgabenstellung</a>
                                    <a class="dropdown-item" href="#" @click.prevent="data.whycontact.body = data.whycontact.templates.fourth">anderer Wohnort</a>
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
                        <div>
                            <input type="radio" id="version1" :value="data.ending.templates.first" v-model="data.ending.body">
                            <label for="version1">Version 1</label>
                        </div>
                        <div>
                            <input type="radio" id="version2" :value="data.ending.templates.second" v-model="data.ending.body">
                            <label for="version2">Version 2</label>
                        </div>
                        <div>
                            <input type="radio" id="version3" :value="data.ending.templates.third" v-model="data.ending.body">
                            <label for="version3">Version 3</label>
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
                    body: "",
                    templates: {
                        first: "Sehr geehrte Damen und Herren,",
                        second: "Sehr geehrte Frau Musterfrau,",
                        third: "Sehr geehrter Herr Mustermann,",
                    }
                },

                awareofyou: {
                    body: "",
                    templates: {
                        first: "mit großem Interesse bin ich im XING Stellenmarkt auf die ausgeschriebene Position aufmerksam geworden. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w).",
                        second: "auf der Suche nach einer neuen Beschäftigung bin ich auf Ihr Unternehmen aufmerksam geworden und komme jetzt initiativ auf Sie zu. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w).",
                        third: "wie telefonisch besprochen, interessiere ich mich sehr für eine Beschäftigung in Ihrem Unternehmen. Aus diesem Grund bewerbe ich mich bei Ihnen um eine Werkstudententätigkeit als Musterstelle (m/w)."
                    }
                },

                currentactivity: {
                    body: "",
                    templates: {
                        first: "Zurzeit arbeite ich als Musterberuf bei Musterfirma. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.",
                        second: "Zurzeit studiere ich Musterstudiengang an der Musterhochschule. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.",
                        third: "Zurzeit befinde ich mich in der Ausbildung als Musterausbildung bei Musterfirma. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.",
                        fourth: "Zurzeit absolviere ich ein Praktikum im Bereich Musterstelle bei Musterfirma. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.",
                        fifth: "Zurzeit besuche ich die Musterschule in Musterort. Zu meinen wichtigsten Aufgaben gehören hierbei die Einarbeitung in neue Produkte, die Durchführung von Verkaufsgesprächen und die Erstellung und Weitergabe von Bestellungen.",
                    }
                },

                whycontact: {
                    body: "",
                    templates: {
                        first: "Ihr Stellenangebot hört sich toll an! Ich hoffe, mir hierdurch persönliche und fachliche Entwicklungsmöglichkeiten erschließen zu können. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.",
                        second: "Nachdem ich schon länger in diesem Bereich tätig bin, suche ich jetzt nach einer neuen Position, in der ich mehr Verantwortung übernehmen kann. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.",
                        third: "Mein Wunsch ist es, die beschriebene Aufgabenstellung als nächsten Schritt für meine weitere berufliche Entwicklung in Ihrem Hause zu nutzen. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.",
                        fourth: "Ich suche an meinem neuen Wohnort eine interessante Beschäftigung und bin daher auf Ihr Unternehmen aufmerksam geworden. Ihre Ausrichtung und das Image in dieser Branche gefallen mir besonders gut, daher sehe ich Sie als einen sehr interessanten Arbeitgeber an. In den Medien habe ich Ihre Entwicklung schon lange verfolgt und glaube daher, auch gut ins Unternehmen zu passen.",
                    }
                },

                wayOfWork: ["zuverlässig", "verantwortungsbewusst", "präzise"],
                skills: ["flexibel", "motiviert", "teamorientiert"],

                ending: {
                    body: "",
                    templates: {
                        first: "Konnte ich Sie mit dieser Bewerbung überzeugen? Ich bin für einen Einstieg zum nächstmöglichen Zeitpunkt verfügbar. Einen vertiefenden Eindruck gebe ich Ihnen gerne in einem persönlichen Gespräch. Ich freue mich über Ihre Einladung!",
                        second: "Ich danke Ihnen für das Interesse an meiner Bewerbung. Zum nächstmöglichen Zeitpunkt bin ich verfügbar. Wenn Sie mehr von mir erfahren möchten, freue ich mich über eine Einladung zum Vorstellungsgespräch.",
                        third: "Ich hoffe, dass Sie einen ersten Eindruck von mir gewinnen konnten. Ein Einstieg zum nächstmöglichen Zeitpunkt ist für mich möglich. Ich freue mich, weitere Details und offene Fragen in einem persönlichen Gespräch auszutauschen.",
                    }
                }
            },

            recentlySaved: false
        };
    },

    mounted() {
        if (!this.data.greeting.body.length) this.data.greeting.body = this.data.greeting.templates.first;
        if (!this.data.awareofyou.body.length) this.data.awareofyou.body = this.data.awareofyou.templates.first;
        if (!this.data.currentactivity.body.length) this.data.currentactivity.body = this.data.currentactivity.templates.first;
        if (!this.data.whycontact.body.length) this.data.whycontact.body = this.data.whycontact.templates.first;
        if (!this.data.ending.body.length) this.data.ending.body = this.data.ending.templates.first;

        if (this.saved.greeting.body.length) this.data.greeting.body = this.saved.greeting.body;
        if (this.saved.awareofyou.body.length) this.data.awareofyou.body = this.saved.awareofyou.body;
        if (this.saved.currentactivity.body.length) this.data.currentactivity.body = this.saved.currentactivity.body;
        if (this.saved.whycontact.body.length) this.data.whycontact.body = this.saved.whycontact.body;
        if (this.saved.wayOfWork.length) this.data.wayOfWork = this.saved.wayOfWork;
        if (this.saved.skills.length) this.data.skills = this.saved.skills;
        if (this.saved.ending.body.length) this.data.ending.body = this.saved.ending.body;
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
