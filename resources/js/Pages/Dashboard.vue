<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                COVID-19 problems
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 shadow-sm sm:rounded-lg flex mt-2">

            <div class="w-1/2 bg-white m-2 rounded">
                <div id="mapId" class="w-full h-96 flex"></div>
            </div>

            <div class="w-1/2 m-2 rounded">
                <div class="bg-white mb-4 p-4 rounded">
                    <div class="flex pb-4">
                        <div class="w-8 mx-4"><img src="https://img.icons8.com/external-prettycons-flat-prettycons/94/000000/external-world-travel-prettycons-flat-prettycons-1.png"/></div>
                        <h2 class="text-2xl text-indigo-600">World</h2>
                    </div>
                    <div class="flex text-xl justify-around text-center">
                        <div>{{ this.wordlCasesData.todayCases }} <br> todayCases</div>
                        <div>{{ this.wordlCasesData.todayDeaths }} <br> todayDeaths</div>
                        <div>{{ this.wordlCasesData.todayRecovered }} <br> todayRecovered</div>
                    </div>
                </div>
                <div class="bg-white p-4">
                    <h1 class="text-2xl text-indigo-600 mb-4">Add your country</h1>
                    <div class="flex">
                        <Select2
                            class="w-full"
                            v-model="SelectedCountry"
                            :settings="{
                                width: '100%',
                                ajax: {
                                    url: '/api/countries/list',
                                    dataType: 'json'
                                    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                                }
                            }"

                        />
                        <a
                            href="javascript:void(0);"
                            @click="addCountry"
                            class="ml-2 px-3 pt-1 text-sm bg-indigo-200 hover:bg-indigo-300 font-semibold shadow rounded"
                        >ADD</a>
                    </div>
                    <div class="py-2" v-for="country in addedCountries" :key="country.id">
                        <div class="w-full mb-1 bg-gray-100 p-3 rounded rounded">
                            <div class="flex mx-4 pb-4 justify-between">
                                <h2 class="text-2xl text-indigo-600">{{ country.name }}</h2>
                                <a href="javascript:void(0)"
                                    @click="removeCountry(country.id)"
                                ><img src="https://img.icons8.com/material-outlined/24/000000/delete-forever.png"/></a>
                            </div>
                            <div class="flex text-xl justify-around text-center">
                                <div>{ xxx } <br> todayCases</div>
                                <div>{ xxx } <br> todayDeaths</div>
                                <div>{ xxx } <br> todayRecovered</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import Select2 from 'vue3-select2-component';
import axios from 'axios';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Select2,
    },
    data(){
        return {
            SelectedCountry: '',
            addedCountries: [],
            map: '',
            wordlCasesData: {},
        }
    },
    methods:{
        getCountries(){
            axios.get('/api/countries')
                .then((response) => {
                    this.addedCountries = response.data;
                });
        },
        getWorldInfo(){
            axios.get('https://disease.sh/v3/covid-19/all')
                .then((response) => (this.wordlCasesData = response.data))
        },
        addCountry(){
            if(this.SelectedCountry !== ''){
                axios.post('/api/add-country', {
                    id: this.SelectedCountry,
                }).then((response) => {

                    this.map.updateChoropleth({
                        [response.data.country_code]: '#E42626'
                    });
                    this.getCountries();
                });
            }else{
                console.warn('you mush select country first!');
                console.log(this.countryCases);
            }
        },
        removeCountry(id){
            axios.delete('/api/remove-country/' + id)
                .then((response) => {
                    this.map.updateChoropleth({
                        [response.data.country_code]: '#0284C7'
                    });
                    this.getCountries();
                });
        },
        initMap(){
            this.map = new Datamap({
                element: document.getElementById("mapId"),
                projection: 'mercator',
                fills: {
                    defaultFill: "#0284C7",
                    authorHasTraveledTo: "rgb(225 29 72)"
                }
            });
        },
    },
    created(){
        this.getWorldInfo();
        this.getCountries();
        setTimeout(() => {
            this.initMap();
        }, 500)
    }
}
</script>
