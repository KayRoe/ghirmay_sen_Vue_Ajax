<template>
  <div id="app">
    <h1>Pet Hotel</h1>
    <div class="main-container">
      <div class="guest-container">
        <h2>Guest</h2>
        <div class="guest-content">
          <div v-if="petsDataError" class="error">
            {{ error }}
          </div>
          <div v-else-if="isLoading" class="loading">
              <Spinner />
              Loading...
            </div>
          <div v-else>
              <div v-for="pet in petsData" :key="pet.id" class="pet-container">
                <button class="pet-info" @click="getPet(pet.id)">
                  <div>
                    <div class="pet-info-row">Pet Name: {{pet.name}}</div>
                    <div class="pet-info-row">Species: {{pet.species}}</div>
                    <div class="pet-info-row">Sex: {{pet.sex}}</div>
                  </div>
                </button>
            </div>
          </div>
        </div>
      </div>

      <div class="details-container">
        <h2>Pet Details</h2>
          <div v-if="petDetailsError" class="error">
            {{ petDetailsError }}
          </div>
          <div v-else-if="petDetails" class="pet-dets">
            <h2>{{ petDetails.name }}</h2>
            <img v-if="petDetails.image_url" :src="petDetails.image_url" />
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
                    this.petDetails.image_url = data.image_url ? `/src/./assets/images/${data.image_url}.jpeg` : 'Not available';
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
h1 {
  color: red;
}

button {
  background-color: none;
}

.main-container {
  display: flex;
  gap: 5rem;
}

 .guest-container{

  display: flex;
  gap: 2rem;
  border-radius: 0.25rem;
  flex-direction: column;
  width: 25%;
  min-width: 10rem;

}

.guest-con tent {
  display:flex;
  flex-direction: column;
}

.pet-dets img{
  width: 15rem;
  height: 15rem;
  border-radius: 20rem;
  object-fit: cover;

}

.pet-container{
  border: 0.2rem solid rgb(250, 204, 204); 
  width: 100%;
}

.pet-info {
  width: 100%;
  padding:20px;
  text-align: left;  
  border: 0.2rem solid blue;  border: none;



}
.pet-container :hover {
color: rgba(227, 42, 42, 0.771);
}


@media screen and (max-width:550px) {
  .main-container {
  gap: 2rem;
}
}

</style>



