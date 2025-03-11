<script setup lang="ts">
import { cn } from '@/lib/utils';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, watch, computed } from 'vue';
import axios from 'axios';

import { Calendar } from '@/components/ui/calendar'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { Button } from '@/components/ui/button'
import {
  DateFormatter,
  type DateValue,
  getLocalTimeZone,
  now,
  Time,
  parseTime
} from '@internationalized/date'
import { CalendarIcon } from 'lucide-vue-next'

type ScheduleValue = {
  day_of_week: string;
  open_time: string;
  close_time: string;
  lunch_start: string;
  lunch_end: string;
  every_other_week: boolean;
  eow_start_date: string;
  // Add other properties as needed
};

/**
 * Data
 */

const schedule = ref<ScheduleValue | null>(null);
const selectedDate = ref<DateValue>();
const isOpen = ref(false);
const df = new DateFormatter('en-US', {
  dateStyle: 'long',
})
const popoverOpen = ref(false);

/**
 * End Data
 */

/**
 * Computed
 */
const openMessage = computed(() => {
    return selectedDate.value ? 'We are open on ' + df.format(selectedDate.value.toDate(getLocalTimeZone())) :'We are open!';
});

/**
 * End Computed
 */

/**
 * Mounted
 */
onMounted(() => {
    fetchSchedule(null);
});


/**
 * End Mounted
 */


/**
 * Watchers
 */
watch(selectedDate, (value) => {
    if (value) {
        console.log('Selected date:', value.toString());
        console.log('value', value);
        popoverOpen.value = false;
        fetchSchedule(value.toString());
    }
});

/**
 * End Watchers
 */



/**
 * Methods
 */
const fetchSchedule = async (date: any) => {
    try {
        // Convert it to ISO format (if you need to send it to Axios)
        const response = await axios.post('/api/check-schedule', { date });
        schedule.value = response.data.schedule;
        isOpen.value = response.data.open;

    } catch (error) {
        console.error('Error fetching schedule:', error);
    }
};


const isLunchTime = () => {
    if (!schedule.value) {
        return false;
    }

    const lunchStart = parseTime(schedule.value.lunch_start);
    const lunchEnd = parseTime(schedule.value.lunch_end);
    const currentDateTime = now(getLocalTimeZone());
    const currentTime = new Time(currentDateTime.hour, currentDateTime.minute);

    return currentTime >= lunchStart && currentTime <= lunchEnd;
};
/**
 * End Methods
 */
</script>

<template>
    <Head title="Welcome"></Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>
        <div class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <div
                    class="flex-1 rounded-bl-lg rounded-br-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:rounded-br-none lg:rounded-tl-lg lg:p-20"
                >
                    <Popover v-model:open="popoverOpen">
                        <PopoverTrigger as-child>
                            <Button
                                variant="outline"
                                :class="cn(
                                'w-[280px] justify-start text-left font-normal',
                                !selectedDate && 'text-muted-foreground',
                                )"
                            >
                                <CalendarIcon class="mr-2 h-4 w-4" />
                                {{ selectedDate ? df.format(selectedDate.toDate(getLocalTimeZone())) : "Pick a date" }}
                            </Button>
                        </PopoverTrigger>
                        <PopoverContent class="w-auto p-0">
                            <Calendar v-model="selectedDate" initial-focus  />
                        </PopoverContent>
                    </Popover>

                    <h2 className="scroll-m-20 border-b pb-2 text-3xl font-semibold tracking-tight mt-6">
                        <span :class="{'text-destructive': !isOpen}">{{ isOpen ? openMessage : 'Sorry, We are close!' }}</span>
                    </h2>
                    
                    <blockquote className="mt-6 border-l-2 pl-6 italic" v-if="isOpen">
                        <template v-if="isLunchTime() && selectedDate === null">
                            It's lunch time! We'll be back at {{ schedule?.lunch_end }}.
                        </template>
                        <template v-else>
                            We are open from {{ schedule?.open_time }} to {{ schedule?.close_time }}.
                        </template>
                    </blockquote>
                    <p className="mt-3 text-xl text-muted-foreground" v-else>

                        We are closed today. Please come back on {{ schedule?.day_of_week }} at {{ schedule?.open_time }}.
                    </p>
                </div>
                
            </main>
        </div>
        <div class="h-14.5 hidden lg:block"></div>
    </div>
</template>