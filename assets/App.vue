<template>
  {{ a }}
fasfasaaa
  <button @click="a++">aaaa</button>
</template>

<script lang="ts" setup>
import { ref} from "vue";
const a = ref(3)

const billy = ref('');

const eventSource = new EventSource("http://127.0.0.1:52265/.well-known/mercure?topic=https%3A%2F%2Fexample.com%2Fbooks%2F1");
eventSource.onmessage = event => {
  // Will be called every time an update is published by the server
  console.log(JSON.parse(event.data));
}
</script>

<style scoped></style>
