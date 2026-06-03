<script setup>
import { ref } from 'vue'

import personagens from '../data/personagens.json'

import CharacterSearch from '../components/CharacterSearch.vue'
import AttemptsList from '../components/AttemptsList.vue'

import { comparar } from '../services/gameService'

const personagemSecreto =
    personagens[Math.floor(Math.random() * personagens.length)]

console.log(personagemSecreto)

const personagemSelecionado = ref(null)

const tentativas = ref([])

const venceu = ref(false)

function tentar() {

    if (!personagemSelecionado.value || venceu.value)
        return

    const tentativa = personagemSelecionado.value

    const resultado = comparar(
        tentativa,
        personagemSecreto
    )

    tentativas.value.push({
        personagem: tentativa,
        resultado
    })

    if (tentativa.id === personagemSecreto.id) {
        venceu.value = true
    }

    personagemSelecionado.value = null
}
</script>

<template>

    <div class="min-h-screen bg-gray-900 text-white p-8">

        <h1 class="text-5xl font-bold text-center mb-8">
            NEWERADLE
        </h1>

        <div v-if="venceu" class="mb-6 p-4 bg-green-600 rounded-lg text-center font-bold text-xl">
            Parabéns! Você acertou o personagem!
        </div>

        <CharacterSearch :personagens="personagens" :venceu="venceu" @select="personagemSelecionado = $event" />

        <button v-if="!venceu" @click="tentar" class="w-full mt-4 py-3 bg-blue-600 rounded-lg">
            Tentar
        </button>

        <div class="mt-8">

            
            <AttemptsList :tentativas="tentativas" />

        </div>

    </div>

</template>