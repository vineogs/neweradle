<script setup>
defineProps({
    tentativa: Object
})
</script>

<template>
    <div class="grid grid-cols-10 gap-2 font-semibold">

        <div class="cell bg-gray-700 reveal delay-0">
            <img :src="tentativa.personagem.imagem" alt="Imagem do personagem" class="w-24 h-24 object-cover rounded">
        </div>

        <div class="cell reveal delay-1 bg-gray-700" :class="tentativa.resultado.nome ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.nome }}
        </div>

        <div class="cell reveal delay-2" :class="tentativa.resultado.genero ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.atributos.genero }}
        </div>

        <div class="cell reveal delay-3" :class="tentativa.resultado.estadoAtual ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.atributos.estadoAtual }}
        </div>

        <div class="cell reveal delay-4" :class="{
            'bg-green-600': tentativa.resultado.idade === 'igual',
            'bg-red-600': tentativa.resultado.idade !== 'igual'
        }">

            <span v-if="tentativa.resultado.idade === 'igual'">
                {{ tentativa.personagem.atributos.idade }}
            </span>

            <span v-else-if="tentativa.resultado.idade === 'mais_velho'">
                {{ tentativa.personagem.atributos.idade }} ↓
            </span>

            <span v-else>
                {{ tentativa.personagem.atributos.idade }} ↑
            </span>

        </div>

        <div class="cell reveal delay-5" :class="{
            'bg-green-600': tentativa.resultado.elemento.status === 'correct',
            'bg-orange-500': tentativa.resultado.elemento.status === 'partial',
            'bg-red-600': tentativa.resultado.elemento.status === 'wrong'
        }">
            {{ tentativa.personagem.atributos.elemento.join(', ') }}
        </div>

        <div class="cell reveal delay-6" :class="tentativa.resultado.afiliacao ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.atributos.afiliacao }}
        </div>

        <div class="cell reveal delay-7" :class="tentativa.resultado.primeiraAparicao ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.atributos.primeiraAparicao }}
        </div>

        <div class="cell reveal delay-8" :class="tentativa.resultado.cabelo ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.atributos.cabelo }}
        </div>

        <div class="cell reveal delay-9" :class="tentativa.resultado.nacionalidade ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.atributos.nacionalidade }}
        </div>

    </div>
</template>

<style scoped>
.cell {
    min-height: 80px;

    display: flex;
    align-items: center;
    justify-content: center;

    text-align: center;
    padding: 8px;

    border-radius: 8px;

    word-break: break-word;
    overflow-wrap: anywhere;
    white-space: normal;
}

.reveal {
    opacity: 0;
    transform: rotateX(90deg);
    animation: revealCell 0.4s ease forwards;
}

.delay-0 {
    animation-delay: 0ms;
}

.delay-1 {
    animation-delay: 150ms;
}

.delay-2 {
    animation-delay: 300ms;
}

.delay-3 {
    animation-delay: 450ms;
}

.delay-4 {
    animation-delay: 600ms;
}

.delay-5 {
    animation-delay: 750ms;
}

.delay-6 {
    animation-delay: 900ms;
}

.delay-7 {
    animation-delay: 1050ms;
}

.delay-8 {
    animation-delay: 1200ms;
}

.delay-9 {
    animation-delay: 1350ms;
}

@keyframes revealCell {
    from {
        opacity: 0;
        transform: rotateX(90deg);
    }

    to {
        opacity: 1;
        transform: rotateX(0);
    }
}
</style>