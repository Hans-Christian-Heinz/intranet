<!-- Formular zum earbeiten der Vorlage für ein Bewerbungsanschreiben -->

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
                <div class="card-body">
                    <!-- Bearbeite Vorlagen für individuelle Abschnitte -->
                    <div class="card" v-for="(temp, key) in variableTpls()">
                        <div class="card-header">
                            <div class="row">
                                <a href="#" class="col-11" @click.prevent="toggle(cards[key])"><h5>Abschnitt {{ key }}</h5></a>
                                <!-- Link zum Löschen eines Abschnitts -->
                                <a v-if="!temp['fix']" href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem" @click.prevent="removeAbschnitt(key)">&#128465</a>
                            </div>
                            <div v-if="!temp['fix']">
                            	<label :for="'number_' + key">Reihenfolge:</label>
                            	<input type="number" class="border-0" style="width:3em" :min="tpl.greeting.number + 1" @change="changeNumber(key)"
                                   :max="tpl.ending.number - 1" :id="'number_' + key" v-model="tpl[key].number"/>
                            </div>
                        </div>
                        <div class="card-body" v-if="cards[key].shown">
                            <!-- fixer Text, in dem nur einige Schlüsselworte ausgewählt werden. -->
                            <div v-if="tpl[key]['chooseKeywords']">
                                <label :for="'full_text_' + key">Vorgeschreibener Text:</label>
                                <textarea :id="'full_text_' + key" class="form-control border-0" @input="changeKwText($event, key)">{{ keywordTpls()[key] }}</textarea>
                                <p>Schlüsselworte:</p>
                                <div style="border: solid lightgrey 1px" class="mb-1" v-for="(help, index) in tpl[key].keywords">
                                    <div class="row">
                                        <label :for="'kw_heading' + index" class="col-11">Überschrift:</label>
                                        <a href="#" class="btn text-danger btn-link col-1" style="font-size: 1.2rem"
                                           @click.prevent="removeKeywordCategory(key, index)">&#128465</a>
                                    </div>
                                    <input type="text" :id="'kw_heading' + index" class="form-control border-0" v-model="tpl[key]['keywords'][index]['heading']"/>
                                    <hr>
                                    <ul>
                                        <li v-for="(kw, ind) in tpl[key]['keywords'][index]['tpls']" class="row">
                                            <input type="text" class="form-control border-0 col-11" v-model="tpl[key]['keywords'][index]['tpls'][ind]"/>
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
                                <input type="text" :id="'heading_' + key" class="form-control border-0" v-model="tpl[key]['heading']"/>

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
                    
                    <!-- Link, um Abschnitte hinzuzufÃ¼gen -->
                    <div class="card">
                        <div class="card-header">
                            <a href="#" @click.prevent="toggle(cards.addSection)"><h5>Abschnitt hinzufÃ¼gen</h5></a>
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