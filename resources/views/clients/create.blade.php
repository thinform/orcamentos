<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Novo Cliente') }}
            </h2>
            <a href="{{ route('clients.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nome *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="cpf_cnpj" class="block text-sm font-medium text-gray-700">CPF/CNPJ *</label>
                                <input type="text" name="cpf_cnpj" id="cpf_cnpj" value="{{ old('cpf_cnpj') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('cpf_cnpj')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Telefone *</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Endereço *</label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="number" class="block text-sm font-medium text-gray-700">Número</label>
                                <input type="text" name="number" id="number" value="{{ old('number') }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="complement" class="block text-sm font-medium text-gray-700">Complemento</label>
                                <input type="text" name="complement" id="complement" value="{{ old('complement') }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('complement')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="neighborhood" class="block text-sm font-medium text-gray-700">Bairro *</label>
                                <input type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('neighborhood')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="zip_code" class="block text-sm font-medium text-gray-700">CEP *</label>
                                <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('zip_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">Cidade *</label>
                                <input type="text" name="city" id="city" value="{{ old('city') }}" required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700">Estado *</label>
                                <select name="state" id="state" required
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Selecione...</option>
                                    <option value="AC" {{ old('state') == 'AC' ? 'selected' : '' }}>Acre</option>
                                    <option value="AL" {{ old('state') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                                    <option value="AP" {{ old('state') == 'AP' ? 'selected' : '' }}>Amapá</option>
                                    <option value="AM" {{ old('state') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                                    <option value="BA" {{ old('state') == 'BA' ? 'selected' : '' }}>Bahia</option>
                                    <option value="CE" {{ old('state') == 'CE' ? 'selected' : '' }}>Ceará</option>
                                    <option value="DF" {{ old('state') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                    <option value="ES" {{ old('state') == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                    <option value="GO" {{ old('state') == 'GO' ? 'selected' : '' }}>Goiás</option>
                                    <option value="MA" {{ old('state') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                                    <option value="MT" {{ old('state') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                    <option value="MS" {{ old('state') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                    <option value="MG" {{ old('state') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                    <option value="PA" {{ old('state') == 'PA' ? 'selected' : '' }}>Pará</option>
                                    <option value="PB" {{ old('state') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                                    <option value="PR" {{ old('state') == 'PR' ? 'selected' : '' }}>Paraná</option>
                                    <option value="PE" {{ old('state') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                    <option value="PI" {{ old('state') == 'PI' ? 'selected' : '' }}>Piauí</option>
                                    <option value="RJ" {{ old('state') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                    <option value="RN" {{ old('state') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                    <option value="RS" {{ old('state') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                    <option value="RO" {{ old('state') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                                    <option value="RR" {{ old('state') == 'RR' ? 'selected' : '' }}>Roraima</option>
                                    <option value="SC" {{ old('state') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                    <option value="SP" {{ old('state') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                                    <option value="SE" {{ old('state') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                                    <option value="TO" {{ old('state') == 'TO' ? 'selected' : '' }}>Tocantins</option>
                                </select>
                                @error('state')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Observações</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Salvar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/imask"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            IMask(document.getElementById('cpf_cnpj'), {
                mask: [
                    {
                        mask: '000.000.000-00',
                        maxLength: 11
                    },
                    {
                        mask: '00.000.000/0000-00',
                        maxLength: 14
                    }
                ]
            });

            IMask(document.getElementById('phone'), {
                mask: [
                    {
                        mask: '(00) 0000-0000'
                    },
                    {
                        mask: '(00) 00000-0000'
                    }
                ]
            });

            IMask(document.getElementById('zip_code'), {
                mask: '00000-000'
            });
        });
    </script>
    @endpush
</x-app-layout> 