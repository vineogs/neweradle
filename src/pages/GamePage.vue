<script setup>
import { ref, onMounted, computed } from 'vue'
import { getPersonagens, tentarPersonagem } from '../services/api.js'

import CountdownTimer from '../components/CountdownTimer.vue'
import CharacterSearch from '../components/CharacterSearch.vue'
import AttemptsList from '../components/AttemptsLists.vue'
import DailyCalendar from '../components/DailyCalendar.vue'

const API_URL = '/public/api'

const mostrarCalendario = ref(false)

const personagens = ref([])

const modo = ref('diario')

const resultado = ref(null)

const personagemSelecionado = ref(null)

const tentativas = ref([])

const bloqueadoHoje = computed(() => {
    const hoje = getHoje()
    const historico = JSON.parse(localStorage.getItem('neweradle_historico') || '{}')
    return !!historico[hoje]?.original
})

const historicoCalendario = ref({})

const streak = ref(0)
const totalWins = ref(0)
const totalGames = ref(0)
const jogosInfinito = ref(0)

const replayData = ref(null)

const diasDoServidor = ref({})

function iniciarJogo() {
    personagemSelecionado.value = null
}

function mudarModo(novoModo) {
    modo.value = novoModo

    if (novoModo === 'infinito') {
        resetInfinito()
    }

    if (novoModo === 'diario') {
        carregarDiario()
    }

    if (novoModo === 'replay') {
        return
    }
}

function podeJogarReplay(entry) {
    return !!entry?.original
}

async function tentar(personagem) {
    const res = await tentarPersonagem({
        modo: modo.value,
        personagemId: personagem.id,
        date: modo.value === 'replay' ? replayData.value.date : null
    })

    tentativas.value.push({
        personagem: res.tentativa,
        dicas: res.dicas,
        isNew: true
    })

    tentativas.value.forEach(t => t.isNew = false)

    resultado.value = res

    const dataJogo = modo.value === 'replay' ? replayData.value.date : getHoje()

    const historico = JSON.parse(
        localStorage.getItem('neweradle_historico') || '{}'
    )

    if (!historico[dataJogo]) {
        historico[dataJogo] = {
            attempts: [],
            original: null
        }
    }

    historico[dataJogo].attempts = [...tentativas.value]

    if (res.acertou) {
        historico[dataJogo].original = {
            win: true,
            attempts: tentativas.value.length
        }
    }

    localStorage.setItem(
        'neweradle_historico',
        JSON.stringify(historico)
    )

    historicoCalendario.value = { ...historico }
    atualizarStats(historico)
}

async function jogarReplay(data) {
    const hoje = getHoje()

    if (data.date == hoje) {
        modo.value = 'diario'
        carregarDiario()
        return
    }

    modo.value = 'replay'

    replayData.value = data

    const historico = JSON.parse(
        localStorage.getItem('neweradle_historico') || '{}'
    )

    const jogo = historico[data.date]

    tentativas.value = jogo?.attempts || []
    resultado.value = jogo?.original || null
}

function getHistorico() {
    historicoCalendario.value = JSON.parse(
        localStorage.getItem('neweradle_historico') || '{}'
    )
}

function getHoje() {
    return new Date().toISOString().split('T')[0]
}

function getYesterday(dateStr) {
    const d = new Date(dateStr)
    d.setDate(d.getDate() - 1)
    return d.toISOString().split('T')[0]
}

function resetJogo() {
    resultado.value = null
    tentativas.value = []
}

function resetInfinito() {
    jogosInfinito.value++

    resultado.value = null
    tentativas.value = []
}

function carregarDiario() {
    const hoje = getHoje()

    const historico = JSON.parse(
        localStorage.getItem('neweradle_historico') || '{}'
    )

    const data = historico[hoje]

    if (data?.attempts) {
        tentativas.value = data.attempts
        resultado.value = data.original
    } else {
        tentativas.value = []
        resultado.value = null
    }
}

async function carregarDias() {
    const res = await fetch(`${API_URL}/daily.php?t=` + Date.now(), {
        cache: 'no-store'
    })

    const text = await res.text()

    diasDoServidor.value = JSON.parse(text)
}

function atualizarStats(historico) {
    const entries = Object.values(historico)

    totalGames.value = entries.length
    totalWins.value = entries.filter(h => h.original?.win).length
    streak.value = calcularStreak(historico)
}

function calcularStreak(historico) {
    let streak = 0

    let dataAtual = new Date()

    const hoje = dataAtual.toISOString().split('T')[0]

    if (!historico[hoje]?.original?.win) {
        dataAtual.setDate(dataAtual.getDate() - 1)
    }

    while (true) {
        const key = dataAtual.toISOString().split('T')[0]

        if (!historico[key]?.original?.win) {
            break
        }

        streak++
        dataAtual.setDate(dataAtual.getDate() - 1)
    }

    return streak
}

onMounted(async () => {
    getHistorico()

    personagens.value = await getPersonagens()

    carregarDiario()

    await carregarDias()

    const hist = JSON.parse(localStorage.getItem('neweradle_historico') || '{}')

    atualizarStats(hist)
})
</script>

<template>
    <div class="min-h-screen bg-gray-900 text-white p-8">

        <DailyCalendar v-if="mostrarCalendario && Object.keys(diasDoServidor).length" :historico="historicoCalendario"
            :days="diasDoServidor" @close="mostrarCalendario = false" @replay="jogarReplay" />

        <div class="absolute top-4 right-4">
            <button @click="mostrarCalendario = !mostrarCalendario"
                class="px-3 py-2 bg-gray-700 rounded-lg text-sm cursor-pointer">
                Ver Calendário
            </button>
        </div>


        <h1 class="mt-12 md:mt-0 text-5xl font-bold text-center mb-8">
            NEWERADLE
        </h1>

        <div class="flex gap-2 justify-center mb-6">
            <button @click="mudarModo('diario')" :class="modo === 'diario' ? 'bg-purple-600' : 'bg-gray-700'"
                class="px-4 py-2 rounded-lg cursor-pointer">
                Diário
            </button>

            <button @click="mudarModo('infinito')" :class="modo === 'infinito' ? 'bg-orange-600' : 'bg-gray-700'"
                class="px-4 py-2 rounded-lg cursor-pointer">
                Infinito
            </button>
        </div>

        <button v-if="modo === 'replay'" @click="mudarModo('diario')" class="w-full my-4 py-3 bg-blue-600 rounded-lg">
            Voltar ao jogo atual
        </button>

        <div v-if="(bloqueadoHoje && modo === 'diario') || resultado?.acertou"
            class="mb-6 p-4 bg-green-600 rounded-lg text-center font-bold text-xl">
            Parabéns! Você acertou o personagem!
        </div>

        <div v-if="modo === 'diario' && (resultado?.acertou || bloqueadoHoje)"
            class="mb-6 p-4 bg-purple-700 rounded-lg text-center">
            <div class="text-lg font-bold">
                Próximo personagem em
            </div>

            <CountdownTimer />
        </div>

        <div v-if="modo !== 'infinito'" class="flex gap-4 justify-center mb-6 text-center">
            <div>
                <div class="text-xl font-bold text-green-400">{{ streak }}</div>
                <div class="text-xs text-gray-400">Streak</div>
            </div>

            <div>
                <div class="text-xl font-bold text-blue-400">{{ totalWins }}</div>
                <div class="text-xs text-gray-400">Vitórias</div>
            </div>

            <div>
                <div class="text-xl font-bold text-gray-300">{{ totalGames }}</div>
                <div class="text-xs text-gray-400">Jogos</div>
            </div>
        </div>

        <div v-else class="flex justify-center mb-6 text-center">
            <div>
                <div class="text-xl font-bold text-orange-400">
                    {{ jogosInfinito }}
                </div>
                <div class="text-xs text-gray-400">
                    Jogos na sessão
                </div>
            </div>
        </div>

        <button v-if="modo === 'infinito' && resultado?.acertou" @click="resetInfinito"
            class="w-full mt-4 py-3 bg-green-600 rounded-lg cursor-pointer">
            Jogar novamente
        </button>

        <CharacterSearch :personagens="personagens" :tentativas="tentativas"
            :disabled="(bloqueadoHoje && modo === 'diario') || resultado?.acertou" @select="tentar" />

        <div class="mt-8">
            <AttemptsList :tentativas="tentativas" titulo="Histórico" />
        </div>
    </div>
</template>