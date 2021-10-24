<template>
    <div v-if="ad_campaigns.length" class="p-10 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-5">
        <div v-for="ad_campaign in ad_campaigns" :key="ad_campaign.id" class="rounded overflow-hidden shadow-lg">
            <img class="w-full" :src="ad_campaign.media[0].full_url" :alt="ad_campaign.name">

            <div class="px-6 py-4">
                <div class="font-bold text-xl mb-2">{{ ad_campaign.name }}</div>

                <Button class="bg-green-600 mr-2" @click="viewAdCampaign(ad_campaign)">
                    View
                </Button>
                <Button class="bg-blue-600 mr-2" @click="editAdCampaign(ad_campaign)">
                    Edit
                </Button>
                <Button class="bg-red-600" @click="deleteAdCampaign(ad_campaign)">
                    Delete
                </Button>
            </div>

            <div class="px-6 pt-4 pb-2">
                <div class="bg-green-200 px-3 py-1 text-sm font-semibold text-gray-700 mb-2">
                    From: <b>{{ this.$root.dateFormat(ad_campaign.date_from, 'ddd, MMMM Do YYYY, h:mm:ss a') }}</b>
                </div>
                <div class="bg-red-200 px-3 py-1 text-sm font-semibold text-gray-700 mb-2">
                    To: <b>{{ this.$root.dateFormat(ad_campaign.date_to, 'ddd, MMMM Do YYYY, h:mm:ss a') }}</b>
                </div>
            </div>
        </div>

        <AdCampaignModal :adCampaign="selectedAdCampaign" @refresh="refreshAdCampaigns" />
    </div>
    <div v-else class="text-blue-600 font-bold">
        You do not have any ad campaigns at the moment.
    </div>
</template>

<script>
import axios from 'axios';
import Button from '@/Components/Button.vue';
import AdCampaignModal from '@/Components/AdCampaignModal.vue';

export default {
    data() {
        return {
            ad_campaigns: [],
            selectedAdCampaign: null,
        }
    },

    components: {
        Button,
        AdCampaignModal,
    },

    watch: {
        ad_campaigns(val) {
            if (this.selectedAdCampaign) {
                this.selectedAdCampaign = this.ad_campaigns.filter(ad_campaign => ad_campaign.id == this.selectedAdCampaign.id)[0]
            }
        }
    },

    methods: {
        fetchCampaigns() {
            axios.get('/api/ad-campaigns')
                .then(response => {
                    this.ad_campaigns = response.data?.data?.ad_campaigns;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        editAdCampaign(ad_campaign) {
            this.$inertia.get(`/ad-campaign/${ad_campaign.id}`)
        },
        viewAdCampaign(ad_campaign) {
            this.selectedAdCampaign = ad_campaign;
        },
        deleteAdCampaign(ad_campaign) {
            axios.delete(`/api/ad-campaigns/${ad_campaign.id}`)
                .then(response => {
                    this.fetchCampaigns();
                })
                .catch(error => {
                    console.error(error);
                });
        },
        refreshAdCampaigns() {
            this.fetchCampaigns();
        }
    },

    mounted() {
        this.fetchCampaigns();
    }
}
</script>
