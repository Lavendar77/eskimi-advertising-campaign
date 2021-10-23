<template>
    <Head :title="(adCampaign ? 'Edit' : 'New') + ' Ad Campaign'" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ (adCampaign ? 'Edit' : 'Create New') }} Ad Campaign
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <AdCampaignForm :adCampaign="adCampaign" :status="status" />
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import AdCampaignForm from '@/Components/AdCampaignForm.vue';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        AdCampaignForm,
    },

    props: {
        status: String,
        adCampaign: {
            type: Object,
            required: false,
        },
    }
}
</script>
