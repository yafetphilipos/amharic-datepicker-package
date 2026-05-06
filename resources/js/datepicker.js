import { EthiopianCalendar } from './ethiopian-calendar.js';

export default function amharicDatepicker(config) {
    return {
        open: false,
        value: config.value || '',
        formattedValue: '',
        displayMonth: null,
        displayYear: null,
        selectedDate: null,
        calendar: new EthiopianCalendar(),
        days: [],
        monthNames: [],
        dayNames: [],
        years: [],

        init() {
            this.monthNames = this.calendar.monthNames;
            this.dayNames = this.calendar.dayNamesMin;
            
            // Generate years array
            for (let i = config.yearEnd; i >= config.yearStart; i--) {
                this.years.push(i);
            }
            
            this.initFromValue();

            this.$watch('value', (val) => {
                this.initFromValue();
            });

            if (config.inline) {
                this.open = true;
            }
        },

        initFromValue() {
            let initialDate;
            if (this.value) {
                let parts = this.value.split('-');
                if (parts.length === 3) {
                    initialDate = {
                        year: parseInt(parts[0]),
                        month: parseInt(parts[1]),
                        day: parseInt(parts[2])
                    };
                }
            }

            if (!initialDate) {
                initialDate = this.calendar.fromJSDate(new Date());
            }

            this.displayMonth = initialDate.month;
            this.displayYear = initialDate.year;
            this.selectedDate = this.value ? initialDate : null;
            
            this.updateCalendar();
            this.formatDisplay();
        },

        updateCalendar() {
            const firstDayOfMonth = this.calendar.toJD(this.displayYear, this.displayMonth, 1);
            const startDayOfWeek = (Math.floor(firstDayOfMonth + 0.5) + 2) % 7;
            
            const daysInMonth = this.calendar.daysInMonth(this.displayYear, this.displayMonth);
            const daysInPrevMonth = this.displayMonth === 1 
                ? this.calendar.daysInMonth(this.displayYear - 1, 13)
                : this.calendar.daysInMonth(this.displayYear, this.displayMonth - 1);

            this.days = [];

            // Previous month days
            for (let i = startDayOfWeek - 1; i >= 0; i--) {
                this.days.push({
                    day: daysInPrevMonth - i,
                    month: this.displayMonth === 1 ? 13 : this.displayMonth - 1,
                    year: this.displayMonth === 1 ? this.displayYear - 1 : this.displayYear,
                    currentMonth: false
                });
            }

            // Current month days
            for (let i = 1; i <= daysInMonth; i++) {
                this.days.push({
                    day: i,
                    month: this.displayMonth,
                    year: this.displayYear,
                    currentMonth: true
                });
            }

            // Next month days
            const remaining = 42 - this.days.length; // Show 6 rows
            for (let i = 1; i <= remaining; i++) {
                this.days.push({
                    day: i,
                    month: this.displayMonth === 13 ? 1 : this.displayMonth + 1,
                    year: this.displayMonth === 13 ? this.displayYear + 1 : this.displayYear,
                    currentMonth: false
                });
            }
        },

        prevMonth() {
            if (this.displayMonth === 1) {
                this.displayMonth = 13;
                this.displayYear--;
            } else {
                this.displayMonth--;
            }
            this.updateCalendar();
        },

        nextMonth() {
            if (this.displayMonth === 13) {
                this.displayMonth = 1;
                this.displayYear++;
            } else {
                this.displayMonth++;
            }
            this.updateCalendar();
        },

        selectDate(day) {
            this.selectedDate = { year: day.year, month: day.month, day: day.day };
            const newValue = `${day.year}-${String(day.month).padStart(2, '0')}-${String(day.day).padStart(2, '0')}`;
            
            this.value = newValue;
            this.formatDisplay();
            
            if (!config.inline) {
                this.open = false;
            }
            
            // Trigger change event for Livewire/other listeners
            this.$dispatch('input', this.value);
            this.$dispatch('change', this.value);
        },

        formatDisplay() {
            if (this.selectedDate) {
                const day = String(this.selectedDate.day).padStart(2, '0');
                const month = String(this.selectedDate.month).padStart(2, '0');
                const year = this.selectedDate.year;
                
                if (config.format === 'dd/mm/yyyy') {
                    this.formattedValue = `${day}/${month}/${year}`;
                } else {
                    this.formattedValue = `${year}-${month}-${day}`;
                }
            }
        },

        isSelected(day) {
            return this.selectedDate && 
                   this.selectedDate.day === day.day && 
                   this.selectedDate.month === day.month && 
                   this.selectedDate.year === day.year;
        },

        isToday(day) {
            const today = this.calendar.fromJSDate(new Date());
            return today.day === day.day && 
                   today.month === day.month && 
                   today.year === day.year;
        }
    };
}
