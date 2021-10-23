<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="p-10 flex justify-between mb-3">
                            <h4 class="font-bold text-3xl">Your Ad Campaigns</h4>

                            <BreezeButton @click="createNewAdCampaign">Create New Ad Campaign</BreezeButton>
                        </div>

                        <AdCampaignList />
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import AdCampaignList from '@/Components/AdCampaignList.vue';
import BreezeButton from '@/Components/Button.vue';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        AdCampaignList,
        BreezeButton
    },

    methods: {
        createNewAdCampaign() {
            this.$inertia.visit(this.route('new-ad-campaign'), {
                method: 'get',
            });
        }
    }
}
</script>
