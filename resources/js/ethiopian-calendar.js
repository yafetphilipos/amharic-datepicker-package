/**
 * Ethiopian Calendar Logic (Vanilla JS)
 * Ported from Keith Wood's jQuery Calendar plugin
 */
export class EthiopianCalendar {
    constructor() {
        this.jdEpoch = 1724220.5;
        this.daysPerMonth = [30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 5];
        this.monthNames = [
            'መስከረም', 'ጥቅምት', 'ኅዳር', 'ታህሣሥ', 'ጥር', 'የካቲት',
            'መጋቢት', 'ሚያዝያ', 'ግንቦት', 'ሰኔ', 'ሐምሌ', 'ነሐሴ', 'ጳጉሜ'
        ];
        this.dayNames = ['እሑድ', 'ሰኞ', 'ማክሰኞ', 'ረቡዕ', 'ሓሙስ', 'ዓርብ', 'ቅዳሜ'];
        this.dayNamesMin = ['እሑ', 'ሰኞ', 'ማክ', 'ረቡ', 'ሐሙ', 'ዓር', 'ቅዳ'];
    }

    leapYear(year) {
        let y = year + (year < 0 ? 1 : 0);
        return y % 4 === 3 || y % 4 === -1;
    }

    daysInMonth(year, month) {
        return this.daysPerMonth[month - 1] + (month === 13 && this.leapYear(year) ? 1 : 0);
    }

    toJD(year, month, day) {
        let y = year;
        if (y < 0) { y++; }
        return day + (month - 1) * 30 + (y - 1) * 365 + Math.floor(y / 4) + this.jdEpoch - 1;
    }

    fromJD(jd) {
        let c = Math.floor(jd) + 0.5 - this.jdEpoch;
        let year = Math.floor((c - Math.floor((c + 366) / 1461)) / 365) + 1;
        if (year <= 0) { year--; }
        
        // Re-calculate JD for start of year to find month/day
        let startOfYearJD = this.toJD(year, 1, 1);
        c = Math.floor(jd) + 0.5 - startOfYearJD;
        let month = Math.floor(c / 30) + 1;
        let day = Math.floor(c - (month - 1) * 30) + 1;
        
        return { year, month, day };
    }

    // Convert from Gregorian Date object to Ethiopian {year, month, day}
    fromJSDate(jsDate) {
        let jd = this.gregorianToJD(jsDate.getFullYear(), jsDate.getMonth() + 1, jsDate.getDate());
        return this.fromJD(jd);
    }

    // Convert Ethiopian to Gregorian Date object
    toJSDate(year, month, day) {
        let jd = this.toJD(year, month, day);
        return this.jdToGregorian(jd);
    }

    gregorianToJD(year, month, day) {
        if (month < 3) {
            year--;
            month += 12;
        }
        let a = Math.floor(year / 100);
        let b = 2 - a + Math.floor(a / 4);
        return Math.floor(365.25 * (year + 4716)) + Math.floor(30.6001 * (month + 1)) + day + b - 1524.5;
    }

    jdToGregorian(jd) {
        let z = Math.floor(jd + 0.5);
        let a = z;
        if (z >= 2299161) {
            let alpha = Math.floor((z - 1867216.25) / 36524.25);
            a = z + 1 + alpha - Math.floor(alpha / 4);
        }
        let b = a + 1524;
        let c = Math.floor((b - 122.1) / 365.25);
        let d = Math.floor(365.25 * c);
        let e = Math.floor((b - d) / 30.6001);
        let day = b - d - Math.floor(30.6001 * e);
        let month = e < 14 ? e - 1 : e - 13;
        let year = month > 2 ? c - 4716 : c - 4715;
        return new Date(year, month - 1, day);
    }
}
