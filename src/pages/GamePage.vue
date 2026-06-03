<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

import CharacterSearch from '../components/CharacterSearch.vue'
import AttemptsList from '../components/AttemptsLists.vue'
import DailyCalendar from '../components/DailyCalendar.vue'

import {
    getPersonagens,
    gerarPersonagem,
    comparar,
    formatarDataChave
} from '../services/gameService'

const personagens = getPersonagens()

const modo = ref('diario')

const personagemSecreto = ref(null)
const personagemSelecionado = ref(null)

const tentativasDiario = ref([])
const tentativasInfinito = ref([])

const venceuDiario = ref(false)
const venceuInfinito = ref(false)

const jogouHoje = ref(false)

const resetKey = ref(0)

const historicoCalendario = ref({})
const mostrarCalendario = ref(false)

function getHoje() {
    return formatarDataChave()
}

function carregarHistorico() {
    try {
        return JSON.parse(localStorage.getItem('historico-calendario')) || {}
    } catch {
        return {}
    }
}

function salvarHistorico(novoHistorico) {
    historicoCalendario.value = novoHistorico
    localStorage.setItem(
        'historico-calendario',
        JSON.stringify(novoHistorico)
    )
}

function carregarTentativas() {
    const hoje = getHoje()

    const data = localStorage.getItem(`${hoje}-tentativas`)
    return data ? JSON.parse(data) : []
}

function salvarTentativas(lista) {
    const hoje = getHoje()

    localStorage.setItem(
        `${hoje}-tentativas`,
        JSON.stringify(lista)
    )
}

function iniciarJogo() {
    const hoje = getHoje()

    if (modo.value === 'diario') {
        const ganhou = localStorage.getItem(hoje)
        const tentativasSalvas = localStorage.getItem(`${hoje}-tentativas`)

        venceuDiario.value = !!ganhou
        jogouHoje.value = !!ganhou || !!tentativasSalvas

        tentativasDiario.value = tentativasSalvas
            ? JSON.parse(tentativasSalvas)
            : []

        if (!venceuDiario.value) {
            personagemSecreto.value = gerarPersonagem('diario')
        }

        return
    }

    if (modo.value === 'infinito') {
        personagemSecreto.value = gerarPersonagem('infinito')
        venceuInfinito.value = false
        tentativasInfinito.value = []
    }

    resetKey.value++
}

function tentar() {
    if (!personagemSelecionado.value) return

    if (modo.value === 'diario' && jogouHoje.value) return
    if (modo.value === 'infinito' && venceuInfinito.value) return

    const tentativa = personagemSelecionado.value
    const resultado = comparar(tentativa, personagemSecreto.value)

    const lista =
        modo.value === 'diario'
            ? tentativasDiario.value
            : tentativasInfinito.value

    lista.push({
        personagem: tentativa,
        resultado
    })

    if (modo.value === 'diario') {
        salvarTentativas(lista)
    }

    const hoje = getHoje()

    if (tentativa.id === personagemSecreto.value.id) {

        if (modo.value === 'diario') {
            venceuDiario.value = true

            const hoje = formatarDataChave()

            localStorage.setItem(hoje, 'win')

            const novoHistorico = {
                ...historicoCalendario.value,
                [hoje]: 'win'
            }

            historicoCalendario.value = novoHistorico

            localStorage.setItem(
                'historico-calendario',
                JSON.stringify(novoHistorico)
            )
        }

        if (modo.value === 'infinito') {
            venceuInfinito.value = true
        }
    }

    personagemSelecionado.value = null
    resetKey.value++
}

function resetInfinito() {
    personagemSecreto.value = gerarPersonagem('infinito')
    tentativasInfinito.value = []
    venceuInfinito.value = false
    resetKey.value++
}

const carregando = ref(true)

const tempoRestante = ref('')

let timer = null

function atualizarContador() {
    const agora = new Date()

    const amanha = new Date()
    amanha.setDate(amanha.getDate() + 1)
    amanha.setHours(0, 0, 0, 0)

    const diff = amanha - agora

    const horas = Math.floor(diff / 1000 / 60 / 60)
    const minutos = Math.floor((diff / 1000 / 60) % 60)
    const segundos = Math.floor((diff / 1000) % 60)

    tempoRestante.value =
        `${String(horas).padStart(2, '0')}:` +
        `${String(minutos).padStart(2, '0')}:` +
        `${String(segundos).padStart(2, '0')}`
}

onMounted(() => {
    historicoCalendario.value = carregarHistorico()
    iniciarJogo()
    carregando.value = false

    atualizarContador()

    timer = setInterval(() => {
        atualizarContador()
    }, 1000)
})

onUnmounted(() => {
    clearInterval(timer)
})
</script>

<template>
    <div class="min-h-screen bg-gray-900 text-white p-8">

        <DailyCalendar v-if="mostrarCalendario" :historico="historicoCalendario" @close="mostrarCalendario = false" />

        <div class="absolute top-4 right-4">
            <button @click="mostrarCalendario = !mostrarCalendario" class="px-3 py-2 bg-gray-700 rounded-lg text-sm">
                {{ mostrarCalendario ? 'Fechar calendário' : 'Ver calendário' }}
            </button>
        </div>

        <h1 class="mt-12 md:mt-0 text-5xl font-bold text-center mb-8">
            NEWERADLE
        </h1>

        <div class="flex gap-2 justify-center mb-6">

            <button @click="modo = 'diario'; iniciarJogo()" :class="modo === 'diario' ? 'bg-purple-600' : 'bg-gray-700'"
                class="px-4 py-2 rounded-lg">
                Diário
            </button>

            <button @click="modo = 'infinito'; iniciarJogo()"
                :class="modo === 'infinito' ? 'bg-orange-600' : 'bg-gray-700'" class="px-4 py-2 rounded-lg">
                Infinito
            </button>

        </div>

        <div v-if="(modo === 'diario' && venceuDiario) || (modo === 'infinito' && venceuInfinito)"
            class="mb-6 p-4 bg-green-600 rounded-lg text-center font-bold text-xl">
            Parabéns! Você acertou o personagem!
        </div>

        <div v-if="modo === 'diario' && venceuDiario" class="mb-6 p-4 bg-purple-700 rounded-lg text-center">
            <div class="text-lg font-bold">
                Próximo personagem em
            </div>

            <div class="text-3xl font-mono mt-2">
                {{ tempoRestante }}
            </div>
        </div>

        <button v-if="modo === 'infinito' && venceuInfinito" @click="resetInfinito"
            class="w-full mt-4 py-3 bg-green-600 rounded-lg">
            Jogar novamente
        </button>

        <CharacterSearch :key="resetKey" :personagens="personagens" :tentativas="modo === 'diario'
            ? tentativasDiario
            : tentativasInfinito"
            :venceu="(modo === 'diario' && venceuDiario) || (modo === 'infinito' && venceuInfinito)"
            @select="personagemSelecionado = $event" @try="tentar" />

        <button v-if="!(
            (modo === 'diario' && jogouHoje) ||
            (modo === 'infinito' && venceuInfinito)
        )" @click="tentar" :disabled="!personagemSelecionado" class="w-full mt-4 py-3 rounded-lg font-bold transition"
            :class="[
                !personagemSelecionado
                    ? 'bg-gray-700 text-gray-400 cursor-not-allowed'
                    : 'bg-blue-600 hover:bg-blue-500 text-white'
            ]">
            {{
                !personagemSelecionado
                    ? 'Selecione um personagem'
                    : 'Tentar'
            }}
        </button>

        <div class="mt-8">
            <AttemptsList :tentativas="modo === 'diario' ? tentativasDiario : tentativasInfinito" titulo="Histórico" />
        </div>
    </div>
</template>