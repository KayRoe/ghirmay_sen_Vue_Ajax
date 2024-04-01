<template>
  <div id="app">
    <h1>Pet Hotel</h1>
    <div>
      <h2>Guest</h2>
      <div v-if="petsDataError" class="error">
        {{ error }}
      </div>
      <div v-else-if="isLoading" class="loading">
          <Spinner />
          Loading...
        </div>
      <div v-else>
          <div v-for="pet in petsData" :key="pet.id">
            <button @click="getPet(pet.id)">
              <div class="pet-info">
                <div class="pet-info-row">Pet Name: {{pet.name}}</div>
                <div class="pet-info-row">Species: {{pet.species}}</div>
                <div class="pet-info-row">Sex: {{pet.sex}}</div>
              </div>
            </button>
        </div>

      <h2>Pet Details</h2>
        <div v-if="petDetailsError" class="error">
          {{ petDetailsError }}
        </div>
        <div v-else-if="petDetails">
          <h2>{{ petDetails.name }}</h2>
          <p>Species: {{ petDetails.species }}</p>
          <p>Sex: {{ petDetails.sex }}</p>
          <p>Age: {{ petDetails.age}}</p>
          <h3>Owner</h3>
          <p>Name: {{ petDetails.owner_name}}</p>
          <p>Email: {{ petDetails.owner_email}}</p>
          <p>Phone Number: {{ petDetails.owner_phone_number}}</p>
        </div>
        <div v-else>
          No pet details available.
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Spinner from './Spinner.vue';

export default {
  created() {
        console.log('loading', this.isLoading)
        fetch('http://localhost:8000/pets')
            .then(res => res.json())
            .then(data => {
                console.log('data', data);
                this.petsData = [];
                data.forEach(petData => {
                    if (petData.id === undefined || petData.id === null) {
                        throw new Error('Invalid pet ID');
                    }
                    const pet = {
                        id: petData.id,
                        name : petData.name ? petData.name : 'Not available',
                        species: petData.species ? petData.species : 'Not available',
                        sex : petData.sex?  petData.sex : 'Not available',
                    }
                    // console.log('petData', petData);
                    this.petsData.push(pet);
                });

                // this.petsData = data;
                // TODO: change this back to false once finishing testing the spinner
                this.isLoading = false;
                console.log("loading", this.isLoading);
                console.log('petData', this.petsData);
            })

            .catch(error => {
                console.error(error)
                //Notify the user on the page an error has occured
                this.petsDataError = `Error fetching pets information: ${error.message}`;
                this.isLoading = false;
            });

        
    },

    data() {
        return {
            petsData : [],
            petDetails : null,
            petsDataError: "",
            isLoading: true,
            petDetailsError: "",
        }
    },

    methods: {
        getPet: function(id) {
            // initialize petDetails
            this.petDetails = null;
            this.petDetailsError = '';
            this.isLoading = true;
            fetch(`http://localhost:8000/pets/${id}`)
                .then(res => res.json())
                .then(data => {
                    this.petDetails = {};
                    this.petDetails.name = data.name ? data.name : 'Not available';
                    this.petDetails.species = data.species ? data.species : 'Not available';
                    this.petDetails.sex = data.sex ? data.sex : 'Not available';
                    this.petDetails.age = data.age ? data.age : 'Not available';
                    this.petDetails.image_url = data.image_url ? data.image_url : 'Not available';
                    this.petDetails.owner_name = data.owner?.name ? data.owner?.name : 'Not available';
                    this.petDetails.owner_email = data.owner?.email? data.owner?.email : 'Not available';
                    this.petDetails.owner_phone_number = data.owner?.phone_number? data.owner?.phone_number : 'Not available';

                    console.log(this.petDetails);
                    this.isLoading = false;
                    console.log("loading end");
                })
                .catch(error => {
                    console.error('Error fetching pet details:', error.message);
                    this.petDetailsError = error.message;
                    this.isLoading = false;
                });
        }
    },
    components: {
      Spinner
    }
};
</script>



<style>
/* CSS  */








</style>



