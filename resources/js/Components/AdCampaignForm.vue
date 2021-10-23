<template>
    <div class="p-6 bg-white border-b border-gray-200">
        <BreezeValidationErrors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <BreezeLabel for="name" value="Name" />
                <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
            </div>

            <div class="mt-4">
                <BreezeLabel for="date_from" value="Date From" />
                <BreezeInput id="date_from" type="datetime-local" class="mt-1 block w-full" v-model="form.date_from" required />
            </div>

            <div class="mt-4">
                <BreezeLabel for="date_to" value="Date To" />
                <BreezeInput id="date_to" type="datetime-local" class="mt-1 block w-full" v-model="form.date_to" required />
            </div>

            <div class="mt-4">
                <BreezeLabel for="total_budget_in_usd" value="Total Budget (USD)" />
                <BreezeInput id="total_budget_in_usd" type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="mt-1 block w-full" v-model="form.total_budget_in_usd" required />
            </div>

            <div class="mt-4">
                <BreezeLabel for="daily_budget_in_usd" value="Daily Budget (USD)" />
                <BreezeInput id="daily_budget_in_usd" type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="mt-1 block w-full" v-model="form.daily_budget_in_usd" required />
            </div>

            <div class="mt-4">
                <BreezeLabel for="banner_images" value="Banner images" />
                <BreezeInput id="banner_images" type="file" class="mt-1 block w-full" @input="form.banner_images = $event.target.files" accept="image/*" :required="!adCampaign" multiple />
                <small class="text-green-600 font-italic" v-if="adCampaign">
                    There are already {{ adCampaign.media.length }} file(s) for this advert.
                </small>
            </div>

            <BreezeButton class="mt-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Submit
            </BreezeButton>
        </form>
    </div>
</template>

<script>
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import BreezeButton from '@/Components/Button.vue';

export default {
    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
    },

    props: {
        status: String,
        adCampaign: {
            type: Object,
            required: false,
        },
    },

    data() {
        return {
            form: this.$inertia.form({
                name: '',
                date_from: '',
                date_to: '',
                total_budget_in_usd: 0,
                daily_budget_in_usd: 0,
                banner_images: null,
                _method: 'POST',
            }),
        }
    },

    methods: {
        submit() {
            if (this.adCampaign) {
                this.form._method = 'PUT';

                this.form.post(`/api/ad-campaigns/${this.adCampaign.id}`, {
                    onSuccess: () => this.form.reset(),
                });

                return;
            }

            this.form.post('/api/ad-campaigns', {
                onSuccess: () => this.form.reset(),
            })
        },
        fillForm(adCampaign) {
            this.form.name = adCampaign.name;
            this.form.date_from = this.$root.dateFormat(adCampaign.date_from, 'YYYY-MM-DDThh:mm');
            this.form.date_to = this.$root.dateFormat(adCampaign.date_to, 'YYYY-MM-DDThh:mm');
            this.form.total_budget_in_usd = adCampaign.total_budget_in_usd;
            this.form.daily_budget_in_usd = adCampaign.daily_budget_in_usd;
        }
    },

    mounted() {
        if (this.adCampaign) {
            this.fillForm(this.adCampaign)
        }
    }
}
</script>