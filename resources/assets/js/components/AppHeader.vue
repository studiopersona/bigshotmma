<template>
	<header class="pageHeader">
		<slot></slot>
	</header>
</template>

<script>
	export default {

		props: ['pageHeader', 'pageSubheader'],

		data: function() {
			return {

			};
		},

		computed: {

		},
	};
</script>