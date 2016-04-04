<template>
    <div class="container">
        <router-view></router-view>
        <!-- Loading Dialog For use by Activities -->
        <div :class="loaderClasses">
            <svg viewBox="0 0 32 32" width="32" height="32">
                <circle id="spinner" cx="16" cy="16" r="14" fill="none"></circle>
            </svg>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
               working: false;
            },
        },

        computed: {
            loaderClasses() {
                    (this.working) ? 'js-global-loader loader' : 'js-global-loader loader visuallyhidden';
            },
        },
    };
</script>