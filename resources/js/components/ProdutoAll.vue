<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import type { Produto } from '@/types';
import Modal from '@/components/ui/modal/Modal.vue'
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Button from './ui/button/Button.vue';
import Label from './ui/label/Label.vue';
import Input from './ui/input/Input.vue';
defineProps<{
    produtos: Produto[]
}>();

const showModal = ref(false)
const createShow = ref(false);
const produtoSelecionado = ref<Produto | null>(null)
const produtos= usePage().props.data
const formulario = ref<{ nome: string; preco: number }>({ nome: '', preco: 0 })
const nome = ref<string>('');
const preco = ref<number | undefined>(undefined)

const editarProduto = (id: number) => {
 console.log(id);
   const produto = produtos.find((p:any) => p.id === id)
  if (produto) {
    produtoSelecionado.value = produto
     formulario.value = {
      nome: produto.nome,
      preco: produto.preco
    }
    showModal.value = true
  }
 
};

const enviarEditacao = () => {
  if (!produtoSelecionado.value) return
console.log(formulario.value);
console.log(produtoSelecionado.value);


  router.put(`/dashboard/produto/${produtoSelecionado.value.id}/edit`,formulario.value, {
    onSuccess: () => {
      showModal.value = false
      produtoSelecionado.value = null
      
    },
    onError: (errors:any) => {
      console.error('Erro ao editar:', errors)
    }
  })
}

const excluirProduto = (id: number, nome: string) => {
    if (confirm(`Tem certeza que deseja excluir o produto "${nome}"?`)) {
        router.delete(`/dashboard/produto/${id}`, {
            onSuccess: () => {
                console.log('Produto excluído com sucesso');
            },
            onError: (errors:any) => {
                console.error('Erro ao excluir produto:', errors);
            }
        });
    }
};

const enviarFormulario = () => {
    if (nome.value.trim() === '' || preco.value === null) {
    alert('Preencha todos os campos')
    return
  }

 const produto: Produto = {
    nome: nome.value,
    preco: preco.value,
  }

  console.log(produto);
  
  router.post("/dashboard/postproduto",{
    nome: produto.nome , 
    preco: produto.preco
  })
}

const formatarPreco = (preco: number) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(preco);
};

const createAbriModal = () => {
    createShow.value = true;
}
const fecharModalCreate =() => {
    createShow.value = false;
}

</script>

<template>
    <div class="w-full">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold ">Produtos</h1>
            <button 
                @click="createAbriModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
            >
                Novo Produto
            </button>
        </div>

        <!-- Verificar se há produtos -->
        <div v-if="produtos.length === 0" class="text-center py-8">
            <p class="text-gray-500 text-lg">Nenhum produto encontrado.</p>
        </div>

        <!-- Tabela de produtos -->
        <div v-else class=" shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nome
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Preço
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="">
                        <tr v-for="produto in produtos" :key="produto.id" class="">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium ">
                                {{ produto.id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm ">
                                {{ produto.nome }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                {{ formatarPreco(produto.preco) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button
                                        @click="editarProduto(produto.id)"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors"
                                        title="Editar produto"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        @click="excluirProduto(produto.id, produto.nome)"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors"
                                        title="Excluir produto"
                                    >
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Informações adicionais -->
        <div class="mt-4 text-sm text-gray-500">
            Total de produtos: {{ produtos.length }}
        </div>

       
        <Modal :show="showModal" @click.self="() => { showModal = false; produtoSelecionado = null }">
  <div v-if="produtoSelecionado" class="space-y-4">
    <h2 class="text-xl font-semibold">Editar Produto</h2>
    
    <div class="space-y-2">
      <label class="block text-sm">Nome:</label>
      <input
        v-model="formulario.nome"
        type="text"
        class="w-full px-3 py-2 border rounded"
      />
      
      <label class="block text-sm">Preço:</label>
      <input
        v-model.number="formulario.preco"
        type="number"
        step="0.01"
        class="w-full px-3 py-2 border rounded"
      />
    </div>

    <div class="flex justify-end gap-2">
      <Button @click="enviarEditacao">
        Editar
      </Button>
      <Button @click="() => { showModal = false; produtoSelecionado = null }">
        Fechar
      </Button>
    </div>
  </div>
</Modal>



    <Modal :show="createShow">
     <form @submit.prevent="enviarFormulario" class="space-y-4 max-w-md">
    <div>
     <Label for="nome">Nome:</Label>
      <Input
        id="nome"
        v-model="nome"
        type="text"
        required
        autocomplete="off"
        class="mt-1 block w-full"
      />
    </div>

    <div>
    <Label for="preco">Preço:</Label>
      <Input
        id="preco"
        v-model.number="preco"
        type="number"
        step="0.01"
        required
        min="0"
        class="mt-1 block w-full"
      />
    </div>

    <div class="flex justify-end gap-2">
      <Button type="submit" class="mt-4">
        Enviar
      </Button>

      <Button type="submit" class="mt-4" @click="fecharModalCreate()">
        Fechar
      </Button>
    </div>
  </form>
  </Modal>

    </div>
</template>