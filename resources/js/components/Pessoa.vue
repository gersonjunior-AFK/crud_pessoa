<template>
  <div class="crud-container">
    <h1>CRUD de Pessoas</h1>
    <p>Cadastre uma pessoa e veja a lista abaixo.</p>

    <form class="form-card" @submit.prevent="cadastrarPessoa">
      <label for="nome">Nome</label>
      <input
        id="nome"
        v-model="form.nome"
        type="text"
        placeholder="Digite o nome"
        required
      />
      <button type="submit" :disabled="carregando">
        {{ carregando ? 'Cadastrando...' : 'Cadastrar' }}
      </button>
    </form>

    <div v-if="mensagem" class="message" :class="mensagemTipo">
      {{ mensagem }}
    </div>

    <section class="lista-card">
      <h2>Pessoas cadastradas</h2>
      <p v-if="pessoas.length === 0">Nenhuma pessoa cadastrada ainda.</p>
      <ul v-else>
        <li v-for="pessoa in pessoas" :key="pessoa.id">
          <span>{{ pessoa.nome }}</span>
          <small>#{{ pessoa.id }}</small>
        </li>
      </ul>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const pessoas = ref([]);
const form = ref({ nome: '' });
const mensagem = ref('');
const mensagemTipo = ref('success');
const carregando = ref(false);

const carregarPessoas = async () => {
  try {
    const { data } = await axios.get('/api/pessoas');
    pessoas.value = data;
  } catch (error) {
    mensagem.value = 'Não foi possível carregar as pessoas.';
    mensagemTipo.value = 'error';
  }
};

const cadastrarPessoa = async () => {
  if (!form.value.nome.trim()) return;

  carregando.value = true;
  mensagem.value = '';

  try {
    const { data } = await axios.post('/api/pessoas', {
      nome: form.value.nome.trim()
    });

    pessoas.value.unshift(data);
    form.value.nome = '';
    mensagem.value = 'Pessoa cadastrada com sucesso!';
    mensagemTipo.value = 'success';
  } catch (error) {
    mensagem.value = 'Erro ao cadastrar pessoa.';
    mensagemTipo.value = 'error';
  } finally {
    carregando.value = false;
  }
};

onMounted(() => {
  carregarPessoas();
});
</script>

<style scoped>
.crud-container {
  max-width: 760px;
  margin: 40px auto;
  padding: 24px;
  font-family: Arial, sans-serif;
  color: #222;
}

h1 {
  margin-bottom: 8px;
  color: #1f3c88;
}

.form-card,
.lista-card {
  background: #f7f9fc;
  border: 1px solid #dce3ee;
  border-radius: 12px;
  padding: 20px;
  margin-top: 20px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

label {
  font-weight: 600;
}

input {
  padding: 10px 12px;
  border: 1px solid #c7d2e0;
  border-radius: 8px;
  font-size: 16px;
}

button {
  background: #1f3c88;
  color: #fff;
  border: none;
  padding: 10px 14px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
}

button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.message {
  margin-top: 16px;
  padding: 10px 12px;
  border-radius: 8px;
}

.message.success {
  background: #eaf7ee;
  color: #1f7a3b;
}

.message.error {
  background: #fdecec;
  color: #b42318;
}

ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

li {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid #e5e7eb;
}

small {
  color: #6b7280;
}
</style>