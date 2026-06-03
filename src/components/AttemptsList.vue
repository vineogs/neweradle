<script setup>
import AttemptRow from './AttemptRow.vue'

defineProps({
    tentativas: Array
})
</script>

<template>

    <div class="flex flex-col gap-2">

        <!-- Cabeçalho -->
        <div class="grid grid-cols-9 gap-2 font-bold text-center">

            <div class="bg-gray-800 p-2 rounded">
                Nome
            </div>

            <div class="bg-gray-800 p-2 rounded">
                Gênero
            </div>

            <div class="bg-gray-800 p-2 rounded">
                Estado
            </div>

            <div class="bg-gray-800 p-2 rounded">
                Idade
            </div>

            <div class="bg-gray-800 p-2 rounded">
                Elemento
            </div>

            <div class="bg-gray-800 p-2 rounded">
                Afiliação
            </div>

            <div class="bg-gray-800 p-2 rounded">
                Aparição
            </div>

            <div class="bg-gray-800 p-2 rounded">
                Cabelo
            </div>

            <div class="bg-gray-800 p-2 rounded">
                País
            </div>

        </div>

        <!-- Tentativas -->
        <AttemptRow v-for="(tentativa, index) in tentativas" :key="index" :tentativa="tentativa" />

    </div>

</template>