<script setup>
defineProps({
    tentativa: Object,
    animar: Boolean
})
</script>

<template>
    <div class="grid grid-cols-9 gap-2 font-semibold">

        <!-- IMG -->
        <div class="cell bg-gray-700">
            <img :src="tentativa.personagem.imagem" alt="Imagem do personagem" loading="lazy"
                class="w-24 h-24 object-cover object-top rounded">
        </div>

        <!-- NOME -->
        <div class="cell bg-gray-700" :class="[
            tentativa.dicas.nome === 'correct' ? 'bg-green-600' : 'bg-red-600',
            { reveal: animar, 'delay-0': animar }
        ]">
            <div class="flex flex-col">
                <span class="font-bold">
                    {{ tentativa.personagem.nome }}
                </span>

                <span class="text-xs opacity-80">
                    {{ Array.isArray(tentativa.personagem.descricao)
                        ? tentativa.personagem.descricao.join(', ')
                        : tentativa.personagem.descricao }}
                </span>
            </div>
        </div>

        <!-- GENERO -->
        <div class="cell" :class="[
            tentativa.dicas.genero === 'correct' ? 'bg-green-600' : 'bg-red-600',
            { reveal: animar, 'delay-1': animar }
        ]">
            {{ tentativa.personagem.atributos.genero }}
        </div>

        <!-- ESTADO -->
        <div class="cell" :class="[
            tentativa.dicas.estadoAtual === 'correct' ? 'bg-green-600' : 'bg-red-600',
            { reveal: animar, 'delay-2': animar }
        ]">
            {{ tentativa.personagem.atributos.estadoAtual }}
        </div>

        <!-- IDADE -->
        <!-- <div class="cell" :class="[
            tentativa.dicas.idade === 'correct' ? 'bg-green-600' : 'bg-red-600',
            { reveal: animar, 'delay-3': animar }
        ]">

            <span v-if="tentativa.dicas.idade === 'correct'">
                {{ tentativa.personagem.atributos.idade }}
            </span>

            <span v-else-if="tentativa.dicas.idade === 'higher'">
                {{ tentativa.personagem.atributos.idade }} ↑
            </span>

            <span v-else>
                {{ tentativa.personagem.atributos.idade }} ↓
            </span>
        </div> -->

        <!-- ELEMENTO -->
        <div class="cell" :class="[
            tentativa.dicas.elemento === 'correct' ? 'bg-green-600'
                : tentativa.dicas.elemento === 'partial' ? 'bg-orange-500'
                    : 'bg-red-600',
            { reveal: animar, 'delay-4': animar }
        ]">
            {{ tentativa.personagem.atributos.elemento.join(', ') }}
        </div>

        <!-- AFILIACAO -->
        <div class="cell" :class="[
            tentativa.dicas.afiliacao === 'correct' ? 'bg-green-600'
                : tentativa.dicas.afiliacao === 'partial' ? 'bg-orange-500'
                    : 'bg-red-600',
            { reveal: animar, 'delay-5': animar }
        ]">
            {{ tentativa.personagem.atributos.afiliacao.join(', ') }}
        </div>

        <!-- APARICAO -->
        <div class="cell" :class="[
            tentativa.dicas.primeiraAparicao === 'correct' ? 'bg-green-600' : 'bg-red-600',
            { reveal: animar, 'delay-6': animar }
        ]">
            {{ tentativa.personagem.atributos.primeiraAparicao }}
        </div>

        <!-- CABELO -->
        <div class="cell" :class="[
            tentativa.dicas.cabelo === 'correct' ? 'bg-green-600'
                : tentativa.dicas.cabelo === 'partial' ? 'bg-orange-500'
                    : 'bg-red-600',
            { reveal: animar, 'delay-7': animar }
        ]">
            {{ tentativa.personagem.atributos.cabelo.join(', ') }}
        </div>

        <!-- NACIONALIDADE -->
        <div class="cell" :class="[
            tentativa.dicas.nacionalidade === 'correct' ? 'bg-green-600' : 'bg-red-600',
            { reveal: animar, 'delay-8': animar }
        ]">
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

/* anima só quando animar === true */
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