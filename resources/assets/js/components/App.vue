<template>
    <div>
        <router-view></router-view>
    </div>
</template>

<script>
	//import auth from '../auth';
	import {router} from '../index';

	export default {
		data: function() {
			return {

				validate () {
					if ( ! auth.validate() ) auth.refresh(this);
				},

			};
		},

		created() {
			//if ( ! auth.validate() ) auth.refresh(this, 'events');
		},

		computed: {

		},
	};
</script>