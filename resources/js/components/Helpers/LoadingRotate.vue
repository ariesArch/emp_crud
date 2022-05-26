<template>
	<div class="text-center">
		<v-progress-circular
			:rotate="360"
			:size="100"
			:width="15"
			:value="loadingTime"
			color="primary"
		>{{ loadingTime }}</v-progress-circular>
	</div>
</template>
<script>
	export default {
		props: {
			loadingTime: {
				type: Number,
				default: () => 0,
			},
		},
	};
</script>
