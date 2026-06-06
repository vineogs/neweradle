const API_URL = '/public/api'

export const getPersonagens = () => request('personagens.php')

export const getDaily = () => request('daily.php')

async function request(url, options) {
    const res = await fetch(`${API_URL}/${url}`, options)

    const text = await res.text()

    try {
        return JSON.parse(text)
    } catch (e) {
        console.error('Resposta inválida:', text)
        return {}
    }
}

export const tentarPersonagem = (payload) =>
    request('tentativa.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })

export async function getDiaPorData(date) {
    const res = await fetch(`${API_URL}/daily.php?date=${date}`)
    return await res.json()
}