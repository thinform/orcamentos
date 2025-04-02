Dados de Acesso ao Banco de Dados
nome: thinforma
usuario: thinforma
servidor: thinforma.mysql.dbaas.com.br
senha: Lordac01#

Dados de acesso ao FTP
host: ftp.thinforma.com.br
url alternativa: ftp.thinforma.hospedagemdesites.ws
usuario: thinforma
senha: Lordac01#Lordac01#
Porta: 21
pasta raiz: /home/thinforma/
pasta do projeto: /public_html/orcamentos

-----------------------------------------------------------

#### 1. **Usuários do sistema**
- Haverá login e controle de usuários?
Sim. Por segurança precisarei criar uma estrutura de Login e Senha de entrada

- O sistema será usado apenas por você ou mais pessoas da equipe?
Será usado inicialmente por mim. Mas pode ser que eu expanda sim para quem trabalhar comigo.


#### 2. **Clientes**
- Você cadastra os clientes com nome, e-mail, telefone, CNPJ, etc.?
Exato. Cadastro de Clientes. Normalmente preciso de NOME: CPF/CNPJ: EMAIL: TELEFONE: ENDEREÇO: CEP: CIDADE: ESTADO:

- Precisa de busca/autocomplete no cadastro de cliente?
Sim. Tudo o que puder ter integrado de mais atual, e que facilite, agilize e ou automatize o sistema.


#### 3. **Produtos**
- São sempre buscados via internet, mas você cadastra eles no sistema antes de montar o orçamento?
Exato. Normalmente eu tenho cadastro CNPJ em Fornecedores de Informática. Mas nem sempre eles tem tudo que vou precisar para montar cada Computador/Projeto. Ai uso a busca pelo Google em Lojas Confiáveis do Brasil, que tenham menor preço para o Produto em questão. 

- Os produtos terão: nome, marca, modelo, descrição, link, valor de custo e margem?
Na tabela atualmente, eu uso somente NOME. UNIDADE. VALOR DE CUSTO. LUCRO. E VALOR FINAL UNITÁRIO. Mas como vai ser um sistema pra ser melhor e mais completo. Vai ser interessante ter mais dados relacionados ao Produto, como, Categoria (Hardwarea...), Subcategoria (Gabinete, Fonte, Processador...), Peso. Medidas (A, L, P). Fotos. Fornecedor. Marca. E etc...


#### 4. **Margem de Lucro**
- A margem é por item ou global?
Vou precisar das 2 opções. Normalmente trabalho com uma margem geral. Mas vai ser interessante ter a opção de margem individual se precisar.

- Você pretende poder alterar em massa todos os produtos de um orçamento?
Sim. 


#### 5. **Formas de Pagamento**
- O sistema precisa mostrar opções como: "À vista", "12x com juros", etc.?
Exato

- O cálculo com acréscimo de 18% sobre o valor à vista para parcelado permanece?
Exato. Calculo para Pagamento à Vista é basicamente: Produto / (100% - Margem de Lucro). Ex 100 / (100 - 15)% = 100 / 85% = 117,6470588235294 (Ai arredondo 0.01 centavo pra cima). Ou seja R$117,65. Para o calculo parcelado é o Valor à Vista / (100 - Custo Parcelamento (que hoje é de 18%) / 10 (numero de parcelas. Logo: 117.65 / 82% = 143,4756097560976 / 10 = 14,35. Resumindo. Um produto que Custa R$100,00 o Valor dele de Venda à Vista seria R$117,65 ou 10x de R$14,35. 


#### 6. **Impressão e Exportação**
- Deseja gerar PDF do orçamento para enviar ao cliente?
Sim. Gerar PDF. E também gerar JPG. ou PNG desse mesmo formato de PDF. 

- Precisa de envio direto por e-mail também?
Não necessariamente. Mas vamos colocar a opção. Hoje envio e atendo quase todos os clientes pelo WhatsApp.


#### 7. **Controle de Orçamentos**
- Deseja um painel com histórico de orçamentos salvos?
Sim. De forma que eu possa voltar nesses orçamentos. Editar. Apagar. Duplicar. e etc

- Pretende ter controle de status (ex: pendente, aprovado, recusado)?
Sim. Excelente ideia

-----------------------------------------------------------

1. Perfeito. Já fiz isso. Todos os passos.

2. Otimo. Segue em anexo a Logo. Tambem a estrutua de Fontes. 
Esquema de Cores (#DD1E21, #4143C1, #FFFFFF, #000000, #737373, #D9D9D9). CNPJ: 54.178.539/0001-64 Nome: THINFORMA Razão Social: Antônio Carlos Sanglard Heringer 
Endereço: www.thinforma.com.br whatsapp (31)99243-1019 email: thinform@gmail.com instagram: @thinforma youtube: @youtube Facebook: @thinforma 

3. Link da Planilha, pois Não sei como enviar arquivo por aqui https://1drv.ms/x/s!Aian09gKyy5ZrUaSpVfZrjbGQss0?e=in4hY6 

-----------------------------------------------------------

Crie um sistema completo em Laravel 11 com Blade, Eloquent ORM e boas práticas de arquitetura (controllers, models, migrations, views), chamado **Sistema de Orçamentos THINFORMA**, para ser hospedado em `orcamentos.thinforma.com.br`.

-----------------------------------------------------------

### FUNCIONALIDADES PRINCIPAIS

1. **Autenticação de Usuários**
   - Login com e-mail e senha.
   - Middleware de proteção para rotas administrativas.
   - Painel de administração (Dashboard).

2. **Cadastro de Clientes**
   - Campos: nome, CPF/CNPJ, e-mail, telefone, endereço, CEP, cidade, estado.
   - CRUD completo.
   - Autocomplete/busca rápida para uso em orçamentos.

3. **Cadastro de Produtos**
   - Campos: código interno, nome, unidade, valor de custo, margem de lucro individual (opcional), valor de venda calculado, categoria, subcategoria, marca, fornecedor, medidas (altura, largura, profundidade), peso, link do fornecedor, imagem.
   - CRUD completo.
   - Cálculo automático de valor de venda à vista e parcelado.

4. **Criação de Orçamentos**
   - Geração de número de orçamento baseado na data/hora (`AAAAHHMMSS`).
   - Autocomplete para cliente e produtos.
   - Adição de múltiplos itens com quantidade e margem específica ou geral.
   - Cálculo automático:
     - Valor à vista = `custo / (1 - margem%)`
     - Valor parcelado = `valor à vista / (1 - 0.18)` dividido por 10x
     - Arredondar todos os valores para cima de R$ 0,01
   - Campos extras: frete (R$), desconto (R$), margem global (opcional).
   - Botão para aplicar margem global a todos os itens do orçamento.
   - Campos de observação geral.
   - Status do orçamento: pendente, aprovado, recusado.
   - Ações disponíveis: Salvar, Editar, Duplicar, Apagar, Exportar PDF/JPG.

5. **Listagem e Histórico de Orçamentos**
   - Filtros por data, cliente, status.
   - Exibição em tabela com ações rápidas.
   - Paginação e ordenação.

6. **Exportação e Compartilhamento**
   - Geração de PDF com layout semelhante ao modelo fornecido (modelo anexo).
   - Geração de imagem JPG/PNG com mesmo layout.
   - Botão para copiar link de visualização ou fazer download.
   - Nome do PDF: `orcamento_{numero}.pdf`.

-----------------------------------------------------------

### ENTIDADES E RELACIONAMENTOS (MODELS E MIGRATIONS)

**User**
- id, name, email, password, timestamps

**Cliente**
- id, nome, cpf_cnpj, email, telefone, endereco, cep, cidade, estado

**Produto**
- id, codigo, nome, unidade, valor_custo, margem_individual, categoria, subcategoria, marca, fornecedor, altura, largura, profundidade, peso, imagem, link

**Orcamento**
- id, numero, cliente_id, user_id, margem_geral, frete, desconto, status, observacoes, created_at, updated_at

**OrcamentoItem**
- id, orcamento_id, produto_id, nome, quantidade, valor_custo, margem_aplicada, valor_unitario_vista, valor_parcelado_unitario, subtotal

-----------------------------------------------------------

### REGRAS DE CÁLCULO

- Valor à vista = custo / (1 - margem%)  
- Valor parcelado = valor à vista / (1 - 0.18)  
- Valor parcelado exibido como: "10x de R$ __"  
- Arredondar todos os valores para cima com incremento mínimo de R$ 0,01

-----------------------------------------------------------

### EXTRAS

- PDF visualmente fiel ao modelo em anexo (`MODELO DE ORÇAMENTOS 00001.pdf`), com dados da empresa, cliente, tabela de produtos, totais, frete, observações, e assinatura.
- O PDF pode conter uma marca d’água discreta "THINFORMA".
- Exportação do mesmo conteúdo do PDF como imagem (JPG/PNG).
- Campos de observação no final do orçamento (ex: validade, garantias, etc).
- O sistema deverá prever expansão para múltiplos usuários no futuro.

-----------------------------------------------------------

Utilize estrutura limpa com controllers separados por módulo (Clientes, Produtos, Orçamentos), com rotas organizadas e uso de migrations para criar as tabelas. Use Eloquent com relacionamentos `hasMany` e `belongsTo`.

Dê preferência à estrutura de projeto compatível com o Filament ou FilamentPHP caso queira usar painel administrativo pronto.


As informações dentro da Caixa abaixo do cabeçalho onde esta: 

"THINFORMA - (31) 99243-1019 - thinform@gmail.com
Rua Tenerife, 407 -
31550-220 - Belo Horizonte, MG
CPF/CNPJ: 54.178.539/0001-64"

Esses campos também devem ser preenchidos com os dados do Cliente em questão na hora de Gerar o orçamento (PDF).

-----------------------------------------------------------

OBSERVAÇÕES. Deixar por padrão essas infos: 
* O Orçamento tem validade 48 horas.
* Máquinas Personalizadas não tem direito a Arrependimento
* Somos Empresa com CNPJ devidamente regularizada
* Todas as peças tem Garantia Oficial e Procedência

-----------------------------------------------------------

Se tiver algo mais que Automatize e ou Facilite a geraçfão de orçamento para meu sistema, onde basicamente eu monto orçamento de computadores para clientes, buscando os menores preços do Mercado (Google, Kabum, Terabyte, Pichau, Gigantec, All Nations, Amazon, Mercado Livre e etc...) 