<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const tempoRestante = ref('')
let timer = null

const amanha = new Date()
amanha.setDate(amanha.getDate() + 1)
amanha.setHours(0, 0, 0, 0)

function atualizarContador() {
    const diff = amanha - new Date()

    const horas = Math.floor(diff / 1000 / 60 / 60)
    const minutos = Math.floor((diff / 1000 / 60) % 60)
    const segundos = Math.floor((diff / 1000) % 60)

    tempoRestante.value =
        `${String(horas).padStart(2, '0')}:` +
        `${String(minutos).padStart(2, '0')}:` +
        `${String(segundos).padStart(2, '0')}`
}

onMounted(() => {
    atualizarContador()
    timer = setInterval(atualizarContador, 1000)
})

onUnmounted(() => {
    clearInterval(timer)
})
</script>

<template>
    <div class="text-3xl font-mono">
        {{ tempoRestante }}
    </div>
</template>