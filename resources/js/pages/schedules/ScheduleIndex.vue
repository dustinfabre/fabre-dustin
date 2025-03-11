ustinfabre/fabre-dustin/resources/js/pages/Schedule.vue
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { BreadcrumbItem } from '@/types';
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import {
  FormControl,
  FormDescription,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form';

defineProps<{
    schedules?: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Schedules',
        href: '/schedule',
    },
];

const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

const scheduleForm = useForm({
    id: null,
    day_of_week: '',
    open_time: '',
    close_time: '',
    lunch_start: '',
    lunch_end: '',
    every_other_week: false,
    eow_start_date: '',
});

const saveSchedule = () => {
    if (scheduleForm.id) {
        scheduleForm.put(`/schedules/${scheduleForm.id}`);
    } else {
        scheduleForm.post('/schedules');
    }
    resetForm();
};

const editSchedule = (schedule: any) => {
    scheduleForm.id = schedule.id;
    scheduleForm.day_of_week = schedule.day_of_week;
    scheduleForm.open_time = schedule.open_time;
    scheduleForm.close_time = schedule.close_time;
    scheduleForm.lunch_start = schedule.lunch_start;
    scheduleForm.lunch_end = schedule.lunch_end;
    scheduleForm.every_other_week = schedule.every_other_week;
    scheduleForm.eow_start_date = schedule.eow_start_date;
};

const deleteSchedule = (id: number) => {
    scheduleForm.delete(`/schedules/${id}`);
};

const resetForm = () => {
    scheduleForm.id = null;
    scheduleForm.day_of_week = '';
    scheduleForm.open_time = '';
    scheduleForm.close_time = '';
    scheduleForm.lunch_start = '';
    scheduleForm.lunch_end = '';
    scheduleForm.every_other_week = false;
    scheduleForm.eow_start_date = '';
};
</script>

<template>
        <Head title="Dashboard" />

        <AppLayout :breadcrumbs="breadcrumbs">
            <div class="container mx-auto p-4">
                <h1 class="text-2xl font-bold mb-4">Manage Schedules</h1>

                <form @submit.prevent="saveSchedule" class="mb-4">
                    <FormField v-slot="{ componentField }" name="day_of_week" label="Day of Week">
                        <FormItem>
                            <FormLabel>Email</FormLabel>
                            <Select  v-bind="componentField">
                                <SelectTrigger class="w-[280px]">
                                    <SelectValue placeholder="Select Day of the week" />
                                </SelectTrigger>
                                <SelectContent>

                                    <SelectGroup>
                                        <SelectItem v-for="day in daysOfWeek" :key="day" :value="day">
                                        {{ day }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormItem>
                    </FormField>

                    <div class="mb-2">
                        <label for="open_time" class="block text-sm font-medium text-gray-700">Open Time</label>
                        <input type="time" id="open_time" v-model="scheduleForm.open_time" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-2">
                        <label for="close_time" class="block text-sm font-medium text-gray-700">Close Time</label>
                        <input type="time" id="close_time" v-model="scheduleForm.close_time" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-2">
                        <label for="lunch_start" class="block text-sm font-medium text-gray-700">Lunch Start</label>
                        <input type="time" id="lunch_start" v-model="scheduleForm.lunch_start" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-2">
                        <label for="lunch_end" class="block text-sm font-medium text-gray-700">Lunch End</label>
                        <input type="time" id="lunch_end" v-model="scheduleForm.lunch_end" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="mb-2">
                        <label for="every_other_week" class="block text-sm font-medium text-gray-700">Every Other Week</label>
                        <input type="checkbox" id="every_other_week" v-model="scheduleForm.every_other_week" class="mt-1 block">
                    </div>
                    <div class="mb-2">
                        <label for="eow_start_date" class="block text-sm font-medium text-gray-700">EOW Start Date</label>
                        <input type="date" id="eow_start_date" v-model="scheduleForm.eow_start_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save</button>
                        <button type="button" @click="resetForm" class="px-4 py-2 bg-gray-600 text-white rounded-md ml-2">Reset</button>
                    </div>
                </form>

                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2">Day of Week</th>
                            <th class="py-2">Open Time</th>
                            <th class="py-2">Close Time</th>
                            <th class="py-2">Lunch Start</th>
                            <th class="py-2">Lunch End</th>
                            <th class="py-2">Every Other Week</th>
                            <th class="py-2">EOW Start Date</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="schedule in schedules" :key="schedule.id">
                            <td class="border px-4 py-2">{{ schedule.day_of_week }}</td>
                            <td class="border px-4 py-2">{{ schedule.open_time }}</td>
                            <td class="border px-4 py-2">{{ schedule.close_time }}</td>
                            <td class="border px-4 py-2">{{ schedule.lunch_start }}</td>
                            <td class="border px-4 py-2">{{ schedule.lunch_end }}</td>
                            <td class="border px-4 py-2">{{ schedule.every_other_week ? 'Yes' : 'No' }}</td>
                            <td class="border px-4 py-2">{{ schedule.eow_start_date }}</td>
                            <td class="border px-4 py-2">
                                <button @click="editSchedule(schedule)" class="px-2 py-1 bg-yellow-500 text-white rounded-md">Edit</button>
                                <button @click="deleteSchedule(schedule.id)" class="px-2 py-1 bg-red-500 text-white rounded-md ml-2">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </AppLayout>

</template>