<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-md-12">
                <div class="card-header text-center">
                  <h1>Flyvinger fra {{flyplass}}</h1>
                </div>
              </div>

                <div class="col-md-12">
                  <div class="row">
                    <div class="text-center offset-md-4 mt-2">
                      <div class="form-group">
                        <input type="text" name="flyplass" placeholder="Velg flypass" v-model="flyplass">
                        <button class="btn btn-primary" @click="getFlights">Hent Flyvinger</button>
                      </div>

                      <div class="form-group">
                        <input type="checkbox" class="form-check-input" @click="show" id="forsinket">
                        <label class="form-check-label" for="forsinket" >Vis Alle flyvinger</label>
                      </div>
                    </div>

                  </div>
                </div>


                  <table class="table table-hover">
                    <thead class="thead-dark">
                      <th scope="col">Selskap</th>
                      <th scope="col">Forventet Flyvingstid</th>
                      <!--<th scope="col">Utsettelse i minutter</th> -->
                      <th scope="col">Gate</th>
                    </thead>
                    <tbody>
                      <div class="mt-2" v-if="delayed.length < 1 && this.searched">
                        <p>Ingen forsinkede avganger fra {{flyplass}}</p>
                      </div>
                      <tr v-for="flight in delayed" :key="flight.uniqueID">
                        <td>{{flight.airline}}</td>
                        <td>{{flight.schedule_time}}</td>
                        <!-- <td v-if="flight.status">{{ (((Date.parse(flight.status["@attributes"].time) - Date.parse(flight.schedule_time)) / 1000) / 60) }}</td> -->
                        <td>{{flight.gate}}</td>
                      </tr>
                    </tbody>
                  </table>
            </div>
    </div>
</template>

<script>
import axios from 'axios';
const API = process.env.MIX_APP_URL;

    export default {

        data() {
          return {
            flights: [],
            flyplass: 'OSL',
            delayed: [],
            showAll: false,
            searched: false,
            airports: [],
          }
        },

        computed: {

        },

        mounted() {
          // populere Airport names
          // working progress. henter ut alle
          // flyplass navn med code fra AVInor og lagrer dem i
          // local DB på server
          // this.getAirportNames();
        },


        methods: {
          async getFlights() {
            let data = await axios.get(`${API}/flights/${this.flyplass}`);
            this.flights = data.data.flight;
            this.filterData();
            this.searched = true;
          },


          filterData(type = 'E') {
            if(type == 'all' || this.showAll == true) {
              return this.delayed = this.flights;
            }
            let count = this.flights.length;
            this.delayed = [];
            for(let i = 0; i < count; i++) {
              let flight = this.flights[i];

              if (flight.status != null) {
                let status = flight.status["@attributes"].code
                if(status == type) {
                  this.delayed.push(flight);
                }
              }
            }
          },


          show() {
            this.showAll = !this.showAll;
            if(this.showAll !== false) {
              this.filterData('all');
            } else {
              this.filterData('E');
            }
          },

          async getAirportNames() {
            let airports = await axios.get(`${API}/airports`);
            this.airports = airports.data;
          }


        }




    }
</script>
