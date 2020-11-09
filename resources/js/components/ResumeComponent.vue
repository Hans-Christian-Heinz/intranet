<template>
    <div>
        <template v-if="statusMessage.length">
            <div id="parent_header"></div>
            <div class="alert alert-info" id="header" :key="statusMessage" v-bind:class="{ 'fixed-top': fixHeader }">
                {{ statusMessage }}
            </div>
        </template>

        <div class="resume-card shadow-sm mb-3">
            <a href="#" class="resume-card-title" @click.prevent="toggle(cards.personalData)">
                <span>Persönliche Daten</span>
                <span class="resume-card-icon">
                    <template v-if="cards.personalData.collapsed">
                        <svg class="bi bi-caret-up" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.204 11L8 5.519 12.796 11H3.204zm-.753-.659l4.796-5.48a1 1 0 011.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 01-.753-1.659z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template v-else>
                        <svg class="bi bi-caret-down" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.204 5L8 10.481 12.796 5H3.204zm-.753.659l4.796 5.48a1 1 0 001.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 00-.753 1.659z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                </span>
            </a>

            <!-- Personal Data Body -->
            <div class="resume-card-body" :style="{ display: cards.personalData.collapsed ? 'block' : 'none' }">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" v-model="resume.personal.name" required>
                </div>
                <div class="form-group">
                    <label for="address">Adresse</label>
                    <input type="text" name="address" id="address" class="form-control" placeholder="Adresse" v-model="resume.personal.address" required>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="zip">PLZ</label>
                        <input type="text" name="zip" id="zip" class="form-control" placeholder="PLZ" v-model="resume.personal.zip" required>
                    </div>
                    <div class="col-md-8">
                        <label for="city">Stadt</label>
                        <input type="text" name="city" id="city" class="form-control" placeholder="Stadt" v-model="resume.personal.city" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Telefon</label>
                    <input type="tel" name="phone" id="phone" class="form-control" placeholder="Telefon Nummer" v-model="resume.personal.phone" required>
                </div>
                <div class="form-group">
                    <label for="email">E-Mail</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="E-Mail Adresse" v-model="resume.personal.email" required>
                </div>
                <div class="form-group mb-0">
                    <label for="birthday">Geboren</label>
                    <input type="date" name="birthday" id="birthday" class="form-control" placeholder="geb." v-model="resume.personal.birthday" required>
                </div>
            </div>
        </div>


        <div class="resume-card shadow-sm mb-3">
            <a href="#" class="resume-card-title" @click.prevent="toggle(cards.school)">
                <span>Schulische Laufbahn</span>
                <span class="resume-card-icon">
                    <template v-if="cards.school.collapsed">
                        <svg class="bi bi-caret-up" width="1em" height="1em" viewBox="0 0 17 17" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.204 11L8 5.519 12.796 11H3.204zm-.753-.659l4.796-5.48a1 1 0 011.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 01-.753-1.659z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template v-else>
                        <svg class="bi bi-caret-down" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.204 5L8 10.481 12.796 5H3.204zm-.753.659l4.796 5.48a1 1 0 001.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 00-.753 1.659z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                </span>
            </a>

            <!-- School body -->
            <div class="resume-card-body" :style="{ display: cards.school.collapsed ? 'block' : 'none' }">
                <table class="table table-hover table-borderless mb-0">
                    <thead>
                    <tr>
                        <th style="width: 30%;">Zeitraum</th>
                        <th style="width: 30%">Schule</th>
                        <th style="width: 30%">Abschluss</th>
                        <th style="width: 5%;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(schule, index) in resume.school" :key="index">
                        <td>
                            <input type="text" name="time" id="sch_time" class="form-control" placeholder="2019 - heute" v-model="schule.time">
                        </td>
                        <td>
                            <textarea name="schule" id="sch_schule" class="form-control" rows="1" placeholder="Schule" v-model="schule.school"></textarea>
                        </td>
                        <td>
                            <textarea name="abschluss" id="sch_abschluss" class="form-control" rows="1" placeholder="Abschluss" v-model="schule.abschluss"></textarea>
                        </td>
                        <td>
                            <a href="#" class="btn text-danger btn-link" @click.prevent="removeSchoolItem(index)">
                                <svg class="bi bi-trash" width="1.4em" height="1.4em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.5 5.5A.5.5 0 016 6v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V6z"/>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 01-1 1H13v9a2 2 0 01-2 2H5a2 2 0 01-2-2V4h-.5a1 1 0 01-1-1V2a1 1 0 011-1H6a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM4.118 4L4 4.059V13a1 1 0 001 1h6a1 1 0 001-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4">Neuen Eintrag <a href="#" @click.prevent="addSchoolItem">Hinzufügen</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="resume-card shadow-sm mb-3">
            <a href="#" class="resume-card-title" @click.prevent="toggle(cards.education)">
                <span>Ausbildung</span>
                <span class="resume-card-icon">
                    <template v-if="cards.education.collapsed">
                        <svg class="bi bi-caret-up" width="1em" height="1em" viewBox="0 0 17 17" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.204 11L8 5.519 12.796 11H3.204zm-.753-.659l4.796-5.48a1 1 0 011.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 01-.753-1.659z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template v-else>
                        <svg class="bi bi-caret-down" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.204 5L8 10.481 12.796 5H3.204zm-.753.659l4.796 5.48a1 1 0 001.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 00-.753 1.659z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                </span>
            </a>

            <!-- Education body -->
            <div class="resume-card-body" :style="{ display: cards.education.collapsed ? 'block' : 'none' }">
                <table class="table table-hover table-borderless mb-0">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Zeitraum</th>
                            <th>Beschreibung</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(education, index) in resume.education" :key="index">
                            <td>
                                <input type="text" name="time" id="time" class="form-control" placeholder="2019 - heute" v-model="education.time">
                            </td>
                            <td>
                                <textarea name="description" id="description" class="form-control" rows="1" placeholder="Beschreibung" v-model="education.description"></textarea>
                            </td>
                            <td>
                                <a href="#" class="btn text-danger btn-link" @click.prevent="removeEducationItem(index)">
                                    <svg class="bi bi-trash" width="1.4em" height="1.4em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.5 5.5A.5.5 0 016 6v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 01-1 1H13v9a2 2 0 01-2 2H5a2 2 0 01-2-2V4h-.5a1 1 0 01-1-1V2a1 1 0 011-1H6a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM4.118 4L4 4.059V13a1 1 0 001 1h6a1 1 0 001-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" clip-rule="evenodd"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">Neuen Eintrag <a href="#" @click.prevent="addEducationItem">Hinzufügen</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="resume-card shadow-sm mb-3">
            <a href="#" class="resume-card-title" @click.prevent="toggle(cards.internships)">
                <span>Praktika</span>
                <span class="resume-card-icon">
                    <template v-if="cards.internships.collapsed">
                        <svg class="bi bi-caret-up" width="1em" height="1em" viewBox="0 0 17 17" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.204 11L8 5.519 12.796 11H3.204zm-.753-.659l4.796-5.48a1 1 0 011.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 01-.753-1.659z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template v-else>
                        <svg class="bi bi-caret-down" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.204 5L8 10.481 12.796 5H3.204zm-.753.659l4.796 5.48a1 1 0 001.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 00-.753 1.659z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                </span>
            </a>

            <!-- Internships body -->
            <div class="resume-card-body" :style="{ display: cards.internships.collapsed ? 'block' : 'none' }">
                <table class="table table-hover table-borderless mb-0">
                    <thead>
                    <tr>
                        <th style="width: 25%;">Zeitraum</th>
                        <th>Firma</th>
                        <th>Beschreibung</th>
                        <th style="width: 5%;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(internship, index) in resume.internships" :key="index">
                        <td>
                            <input type="text" name="time" id="int_time" class="form-control" placeholder="2019 - 2020" v-model="internship.time">
                        </td>
                        <td>
                            <input type="text" name="company" id="int_company" class="form-control" placeholder="Praktikumsbetrieb" v-model="internship.company">
                        </td>
                        <td>
                            <textarea name="description" id="internship" class="form-control" rows="1" placeholder="Praktikumsinhalt" v-model="internship.description"></textarea>
                        </td>
                        <td>
                            <a href="#" class="btn text-danger btn-link" @click.prevent="removeInternshipItem(index)">
                                <svg class="bi bi-trash" width="1.4em" height="1.4em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.5 5.5A.5.5 0 016 6v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V6z"/>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 01-1 1H13v9a2 2 0 01-2 2H5a2 2 0 01-2-2V4h-.5a1 1 0 01-1-1V2a1 1 0 011-1H6a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM4.118 4L4 4.059V13a1 1 0 001 1h6a1 1 0 001-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3">Neuen Eintrag <a href="#" @click.prevent="addInternshipItem">Hinzufügen</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="resume-card shadow-sm mb-3">
            <a href="#" class="resume-card-title" @click.prevent="toggle(cards.skills)">
                <span>Kenntnisse & Fähigkeiten</span>
                <span class="resume-card-icon">
                    <template v-if="cards.skills.collapsed">
                        <svg class="bi bi-caret-up" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.204 11L8 5.519 12.796 11H3.204zm-.753-.659l4.796-5.48a1 1 0 011.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 01-.753-1.659z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template v-else>
                        <svg class="bi bi-caret-down" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.204 5L8 10.481 12.796 5H3.204zm-.753.659l4.796 5.48a1 1 0 001.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 00-.753 1.659z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                </span>
            </a>

            <!-- Skills body -->
            <div class="resume-card-body" :style="{ display: cards.skills.collapsed ? 'block' : 'none' }">
                <table class="table table-hover table-borderless mb-0">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Title</th>
                            <th>Beschreibung</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(skill, index) in resume.skills" :key="index">
                            <td>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Fremdspachen" v-model="skill.title">
                            </td>
                            <td>
                                <textarea name="description" id="description" class="form-control" rows="1" placeholder="Englisch sehr gut in Wort und Schrift" v-model="skill.description"></textarea>
                            </td>
                            <td>
                                <a href="#" class="btn text-danger btn-link" @click.prevent="removeSkillItem(index)">
                                    <svg class="bi bi-trash" width="1.4em" height="1.4em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.5 5.5A.5.5 0 016 6v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 01-1 1H13v9a2 2 0 01-2 2H5a2 2 0 01-2-2V4h-.5a1 1 0 01-1-1V2a1 1 0 011-1H6a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM4.118 4L4 4.059V13a1 1 0 001 1h6a1 1 0 001-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" clip-rule="evenodd"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">Neuen Eintrag <a href="#" @click.prevent="addSkillItem">Hinzufügen</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="resume-card shadow-sm mb-3">
            <a href="#" class="resume-card-title" @click.prevent="toggle(cards.career)">
                <span>Berufliche Laufbahn</span>
                <span class="resume-card-icon">
                    <template v-if="cards.career.collapsed">
                        <svg class="bi bi-caret-up" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.204 11L8 5.519 12.796 11H3.204zm-.753-.659l4.796-5.48a1 1 0 011.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 01-.753-1.659z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template v-else>
                        <svg class="bi bi-caret-down" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.204 5L8 10.481 12.796 5H3.204zm-.753.659l4.796 5.48a1 1 0 001.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 00-.753 1.659z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                </span>
            </a>


            <!-- Career body -->
            <div class="resume-card-body" :style="{ display: cards.career.collapsed ? 'block' : 'none' }">
                <table class="table table-hover table-borderless mb-0">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Zeitraum</th>
                            <th>Firma</th>
                            <th>Beschreibung</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(career, index) in resume.careers" :key="index">
                            <td>
                                <input type="text" name="time" id="time" class="form-control" placeholder="2019 - 2020" v-model="career.time">
                            </td>
                            <td>
                                <input type="text" name="company" id="company" class="form-control" placeholder="Paulinenpflege Winnenden e.V." v-model="career.company">
                            </td>
                            <td>
                                <textarea name="description" id="description" class="form-control" rows="1" placeholder="Praktikum Fachinformatiker Systemintegration" v-model="career.description"></textarea>
                            </td>
                            <td>
                                <a href="#" class="btn text-danger btn-link" @click.prevent="removeCareerItem(index)">
                                    <svg class="bi bi-trash" width="1.4em" height="1.4em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.5 5.5A.5.5 0 016 6v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 01-1 1H13v9a2 2 0 01-2 2H5a2 2 0 01-2-2V4h-.5a1 1 0 01-1-1V2a1 1 0 011-1H6a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM4.118 4L4 4.059V13a1 1 0 001 1h6a1 1 0 001-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" clip-rule="evenodd"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">Neuen Eintrag <a href="#" @click.prevent="addCareerItem">Hinzufügen</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="resume-card shadow-sm mb-3">
            <a href="#" class="resume-card-title" @click.prevent="toggle(cards.interests)">
                <span>Interessen</span>
                <span class="resume-card-icon">
                    <template v-if="cards.interests.collapsed">
                        <svg class="bi bi-caret-up" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.204 11L8 5.519 12.796 11H3.204zm-.753-.659l4.796-5.48a1 1 0 011.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 01-.753-1.659z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template v-else>
                        <svg class="bi bi-caret-down" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.204 5L8 10.481 12.796 5H3.204zm-.753.659l4.796 5.48a1 1 0 001.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 00-.753 1.659z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                </span>
            </a>


            <!-- interests body -->
            <div class="resume-card-body" :style="{ display: cards.interests.collapsed ? 'block' : 'none' }">
                <table class="table table-hover table-borderless mb-0">
                    <thead>
                    <tr>
                        <th>Interesse</th>
                        <th style="width: 5%;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(interest, index) in resume.interests" :key="index">
                        <td>
                            <textarea name="interest" id="interest" class="form-control" rows="1" placeholder="Interesse" v-model="interest.interest"></textarea>
                        </td>
                        <td>
                            <a href="#" class="btn text-danger btn-link" @click.prevent="removeInterestItem(index)">
                                <svg class="bi bi-trash" width="1.4em" height="1.4em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.5 5.5A.5.5 0 016 6v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V6z"/>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 01-1 1H13v9a2 2 0 01-2 2H5a2 2 0 01-2-2V4h-.5a1 1 0 01-1-1V2a1 1 0 011-1H6a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM4.118 4L4 4.059V13a1 1 0 001 1h6a1 1 0 001-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">Neuen Eintrag <a href="#" @click.prevent="addInterestItem">Hinzufügen</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p>
            Beachten Sie: Beim Drucken des Lebenslaufs können Sie ein Passbild sowie eine Signatur hochladen, wenn  Sie möchten.
            Diese (Bild-)Dateien werden von dieser Applikation nicht gespeichert.
        </p>

        <div class="form-group d-flex">
            <a class="btn btn-outline-primary ml-auto" data-toggle="modal" href="#formatPdf">Drucken</a>
        </div>
    </div>
</template>

<script>
export default {
    props: ["user", "resumedata", "printroute"],

    data() {
        return {
            cards: {
                personalData: { collapsed: false },
                school: { collapsed: false },
                education: { collapsed: false },
                internships: { collapsed: false },
                skills: { collapsed: false },
                career: { collapsed: false },
                interests: { collapsed: false }
            },

            resume: {
                personal: {
                    name: "",
                    address: "",
                    city: "",
                    zip: "",
                    phone: "",
                    email: "",
                    birthday: ""
                },

                school: [],

                education: [],

                internships: [],

                skills: [],

                careers: [],

                interests: [],
            },

            statusMessage: "Änderungen werden automatisch gespeichert.",
            timesSaved: 0,
            throttleInterval: null,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            fixHeader: false
        };
    },

    mounted() {
        axios.get(`/bewerbungen/resumes/${this.user.id}`)
            .then(response => response.data)
            .then(data => {
                if (data) {
                    this.resume = data;
                } else {
                    this.resume.personal.name = this.user.full_name;
                    this.resume.personal.address = this.user.strasse + " " + this.user.hausnr;
                    this.resume.personal.zip = this.user.plz;
                    this.resume.personal.city = this.user.ort;
                    this.resume.personal.email = this.user.email;

                    this.save();
                }
            })
            .catch(error => {
                console.log(error);
            });
    },

    created() {
        //lodash throttle: Methode wird nicht öfter als alle 100ms aufgerufen
        //Wenn sie davor aufgerufen wird, wird das Ergebnis nicht neu berechnet
        this.handleThrottledScroll = _.throttle(this.handleScroll, 100);
        window.addEventListener('scroll', this.handleThrottledScroll);
    },

    watch: {
        resume: {
            deep: true,

            handler() {
                clearInterval(this.throttleInterval);

                if (this.timesSaved >= 1) {
                    this.statusMessage = "Die aktuellen Änderungen wurden noch nicht gespeichert";
                }

                this.throttleInterval = setTimeout(() => {
                    this.save();
                }, 1000);
            }
        }
    },

    methods: {
        save() {
            this.timesSaved++;

            if (this.timesSaved > 1) {
                axios.post(`/bewerbungen/resumes/${this.user.id}`, {resume: this.resume})
                    .then(response => response.data)
                    .then(data => {
                        this.statusMessage = "Die Änderungen wurden erfolgreich gespeichert.";

                        setTimeout(() => {
                            this.statusMessage = "";
                        }, 5000);
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            }

        },

        toggle(card) {
            card.collapsed = !card.collapsed;
        },

        addSchoolItem() {
            this.resume.school.push({time: "", school: "", abschluss: ""});
        },

        removeSchoolItem(index) {
            this.resume.school.splice(index, 1);
        },

        addEducationItem() {
            this.resume.education.push({time: "", description: ""});
        },

        removeEducationItem(index) {
            this.resume.education.splice(index, 1);
        },

        addInternshipItem() {
            this.resume.internships.push({time: "", company: "", description: ""});
        },

        removeInternshipItem(index) {
            this.resume.internships.splice(index, 1);
        },

        addSkillItem() {
            this.resume.skills.push({title: "", description: ""});
        },

        removeSkillItem(index) {
            this.resume.skills.splice(index, 1);
        },

        addCareerItem() {
            this.resume.careers.push({time: "", company: "", description: ""});
        },

        removeCareerItem(index) {
            this.resume.careers.splice(index, 1);
        },

        addInterestItem() {
            this.resume.interests.push({interest: ""});
        },

        removeInterestItem(index) {
            this.resume.interests.splice(index, 1);
        },

        handleScroll(e) {
            if ($('#header').length) {
                this.fixHeader = window.pageYOffset > $('#parent_header').offset().top;
            }
        }
    }
}
</script>

<style>
    .resume-card-title {
        display: flex;
        justify-content: space-between;

        /* border-bottom: 1px solid #eee; */
        background-color: white;
        padding: 20px 20px;
        color: var(--text-secondary);
        text-decoration: none!important;
    }

    .resume-card-body {
        background-color: white;
        padding: 20px 20px;
        border-top: 1px solid #eee;
    }

    .resume-card-body .table thead tr th {
        padding-top: 0;
        padding-bottom: 0;
    }
</style>
