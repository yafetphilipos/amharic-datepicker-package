<div 
    x-data="amharicDatepicker({ 
        value: '{{ $value }}', 
        format: '{{ $format }}', 
        locale: '{{ $locale }}',
        inline: {{ $inline ? 'true' : 'false' }},
        yearStart: {{ $yearStart }},
        yearEnd: {{ $yearEnd }}
    })" 
    {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}
    class="ethiopian-datepicker"
    @click.away="open = false"
    x-modelable="value"
>
    @if(!$inline)
        <div class="datepicker-input-wrapper">
            <input 
                type="text" 
                id="{{ $id }}" 
                {{ $attributes->whereDoesntStartWith(['wire:model', 'x-model', 'class']) }}
                x-model="formattedValue"
                @focus="open = true"
                @input="handleManualInput"
                class="{{ $attributes->get('class') }} datepicker-input @error($name) has-error @enderror"
                placeholder="{{ $format === 'dd/mm/yyyy' ? 'ቀን/ወር/አመት' : 'አመት-ወር-ቀን' }}"
            >
            <input type="hidden" name="{{ $name }}" x-model="value">
            
            @if(isset($error))
                {{ $error }}
            @else
                @error($name)
                    <div class="datepicker-error-container">
                        <svg class="datepicker-error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <p class="datepicker-error-message">{{ $message }}</p>
                    </div>
                @enderror
            @endif
        </div>
    @endif

    <div 
        x-show="open" 
        x-transition
        class="{{ $inline ? 'ethiopian-datepicker-inline' : 'ethiopian-datepicker-popup' }}"
    >
        <div class="datepicker-header">
            <button type="button" @click="prevMonth" class="datepicker-nav-btn">&larr;</button>
            
            <div class="datepicker-selectors">
                <select x-model="displayMonth" @change="updateCalendar" class="datepicker-select">
                    <template x-for="(name, index) in monthNames">
                        <option :value="index + 1" x-text="name" :selected="displayMonth == index + 1"></option>
                    </template>
                </select>

                <select x-model="displayYear" @change="updateCalendar" class="datepicker-select">
                    <template x-for="year in years">
                        <option :value="year" x-text="year" :selected="displayYear == year"></option>
                    </template>
                </select>
            </div>

            <button type="button" @click="nextMonth" class="datepicker-nav-btn">&rarr;</button>
        </div>

        <div class="datepicker-grid">
            <template x-for="name in dayNames">
                <div class="datepicker-day-name" x-text="name"></div>
            </template>

            <template x-for="day in days">
                <div 
                    @click="selectDate(day)"
                    class="datepicker-day"
                    :class="{ 
                        'not-current': !day.currentMonth,
                        'is-selected': isSelected(day),
                        'is-today': isToday(day)
                    }"
                    x-text="day.day"
                ></div>
            </template>
        </div>
    </div>

    @if($inline)
        @if(isset($error))
            {{ $error }}
        @else
            @error($name)
                <div class="datepicker-error-container">
                    <svg class="datepicker-error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <p class="datepicker-error-message">{{ $message }}</p>
                </div>
            @enderror
        @endif
    @endif
</div>
