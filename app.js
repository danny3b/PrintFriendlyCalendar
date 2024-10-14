new Vue({
    el: '#app',
    data: {
        year: new Date().getFullYear(),
        month: new Date().getMonth(), // 0-11, więc styczeń to 0
    },
    computed: {
        monthName() {
            const months = [
                'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 
                'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 
                'Wrzesień', 'Październik', 'Listopad', 'Grudzień'
            ];
            return months[this.month];
        },
        calendar() {
            const firstDay = new Date(this.year, this.month, 1);
            const lastDay = new Date(this.year, this.month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const firstDayOfWeek = firstDay.getDay() === 0 ? 7 : firstDay.getDay(); // 1 = poniedziałek, ..., 7 = niedziela
            const daysInPreviousMonth = new Date(this.year, this.month, 0).getDate();

            const calendar = [];
            let week = [];

            // Ustalanie dni z poprzedniego miesiąca
            for (let i = 0; i < firstDayOfWeek - 1; i++) {
                week.push({ day: daysInPreviousMonth - firstDayOfWeek + i + 2, isOutside: true });
            }

            // Dni z bieżącego miesiąca
            for (let i = 1; i <= daysInMonth; i++) {
                week.push({ day: i, isOutside: false });
                if (week.length === 7) { // Po każdym 7 dni dodajemy tydzień do kalendarza
                    calendar.push(week);
                    week = []; // Resetujemy tydzień
                }
            }

            // Uzupełnienie dni z następnego miesiąca
            let nextMonthDay = 1;
            while (week.length < 7) {
                week.push({ day: nextMonthDay++, isOutside: true });
            }

            // Dodajemy ostatni tydzień do kalendarza, jeśli jest niepusty
            if (week.length > 0) {
                calendar.push(week);
            }

            return calendar;
        }
    },
    methods: {
        prevMonth() {
            if (this.month === 0) {
                this.month = 11;
                this.year--;
            } else {
                this.month--;
            }
        },
        nextMonth() {
            if (this.month === 11) {
                this.month = 0;
                this.year++;
            } else {
                this.month++;
            }
        },
        handleKeydown(event) {
            if (event.key === 'ArrowLeft') {
                this.prevMonth();
            } else if (event.key === 'ArrowRight') {
                this.nextMonth();
            }
        }
    },
    mounted() {
        window.addEventListener('keydown', this.handleKeydown);
    },
    beforeDestroy() {
        window.removeEventListener('keydown', this.handleKeydown);
    }
});
