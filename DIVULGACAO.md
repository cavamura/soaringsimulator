# IFR Training Simulator

**Treinamento de navegação aérea por instrumentos — direto no navegador, sem instalação.**

---

## O que é o IFR Training Simulator?

O IFR Training Simulator é uma ferramenta de treinamento para pilotos e estudantes de aviação que desejam praticar **navegação VOR** e o uso de **instrumentos de voo por instrumentos (IFR)** — sem precisar de um simulador caro, sem instalação de software e sem depender de conectividade com servidores externos.

Desenvolvido inteiramente em tecnologia web (HTML5 + Canvas), funciona em qualquer navegador moderno — no computador ou no celular.

---

## Para quem é?

- Estudantes de pilotagem que ainda não possuem horas de voo IFR
- Pilotos em preparação para a checagem de instrumentos
- Instrutores de voo que precisam demonstrar conceitos de navegação VOR em sala de aula
- Entusiastas da aviação que querem aprender como funciona a navegação por rádio

---

## Funcionalidades

### Mapa de Navegação (Seção A)

- **Vista aérea superior** com grade de orientação e rastro de trajetória opcional
- **Até 3 estações VOR** posicionadas aleatoriamente no mapa a cada sessão — cada uma com nome e frequência VHF
- **Transferidor de radiais** visual ao redor de cada VOR, com marcações de 10 em 10 graus e rótulos a cada 30°
- **Fundo configurável**: mapa estático ou **Google Maps** integrado (vista de mapa ou satélite), com localização e zoom personalizáveis
- **Modo de treinamento às cegas**: opção de ocultar a aeronave e as estações VOR para simular condições IMC reais — o piloto navega apenas pelos instrumentos
- **Joystick virtual** para controle por toque em dispositivos móveis
- **Reposicionamento interativo**: arraste a aeronave para qualquer ponto do mapa e rotacione seu proa com um segundo handle — ou reposicione qualquer estação VOR arrastando-a diretamente no mapa

### Cockpit (Seção B)

- **Horizonte artificial** com indicação contínua de atitude:
  - Ângulo de banco (roll) com arco de referência e marcações ±10°, ±20°, ±30°, ±45°, ±60°, ±70°
  - Linhas de pitch de ±5° até ±30°
- **Fita de rumo** (heading tape) exibida no topo do instrumento
- **Caixas laterais** com velocidade e altitude da aeronave

### Painel IFR (Seção C)

- **RMI — Radio Magnetic Indicator**: exibe o rumo magnético atual (lubber line) e um ponteiro vermelho apontando para a estação VOR sintonizada
- **Indicador de Direção / Proa (Heading Bug)**: seletor visual rotacionável por arraste ou scroll do mouse para definir a proa desejada
- **Piloto Automático**: ative o AP e a aeronave curva automaticamente até estabilizar na proa selecionada
- **NAV / DME**: sintonize a frequência de qualquer VOR e acompanhe em tempo real:
  - Nome e frequência da estação sintonizada
  - **DME** (Distance Measuring Equipment) — distância até a estação em milhas náuticas
  - **Radial atual** e **Course** em graus

### Controles

| Ação | Teclado | Botão / Toque |
|---|---|---|
| Curvar esquerda | ← | Botão ← |
| Curvar direita | → | Botão → |
| Aumentar velocidade | ↑ | Botão ↑ |
| Reduzir velocidade | ↓ | Botão ↓ |
| Pausar / Retomar | P | Pause |
| Abrir manual | H | Link Ajuda |
| Pilotar (mobile) | — | Joystick virtual |

### Configurações e Sessão

- Ativar/desativar rastro de trajetória da aeronave
- Modo invisível: treino apenas por instrumentos
- Fundo Google Maps com seleção de localização e nível de zoom
- URL de log opcional para registrar dados de voo em servidor remoto ou Google Apps Script (útil para instrutores acompanharem sessões)
- Botão **Reset** para iniciar nova missão com VORs em posições aleatórias diferentes

---

## Benefícios

### Acessível e gratuito
Abre em qualquer navegador — Chrome, Firefox, Edge, Safari. Não requer criação de conta, download ou pagamento. Basta abrir o arquivo e começar a treinar.

### Fiel aos procedimentos reais
A lógica de navegação VOR — sintonização de frequência, leitura de radial, cálculo de bearing e DME — reproduz os princípios dos sistemas reais de navegação VHF. O aluno aprende com os mesmos conceitos que usará em aeronaves reais.

### Progressão de dificuldade
Comece com aeronave e VORs visíveis para entender a geometria da navegação. Avance para o **modo invisível**, onde apenas os instrumentos guiam o voo — exatamente como num voo real em IMC.

### Mobilidade total
Funciona em tablets e smartphones com suporte a toque multitoque. O joystick virtual e os gestos de arraste permitem treinamento em qualquer lugar.

### Útil em sala de aula
Instrutores podem usar o simulador projetado para demonstrar ao vivo: como o RMI se comporta ao se aproximar de um VOR, como o AP mantém uma proa, como o DME diminui progressivamente conforme a aeronave se aproxima da estação.

### Personalizável por localização
Com o Google Maps integrado, é possível treinar navegação sobre a área geográfica real onde o aluno vai voar — usando as mesmas referências visuais do solo que encontrará numa descida real.

---

## Tecnologia

- Desenvolvido inteiramente em **HTML5, CSS3 e JavaScript puro** — sem frameworks, sem dependências externas
- Renderização por **Canvas 2D** de alta performance
- Compatível com **Pointer Events API** para suporte unificado a mouse, touch e caneta stylus
- Registro opcional de dados de sessão via **Google Apps Script** ou qualquer endpoint REST

---

## Contato

**Desenvolvedor:** L. Cavamura Jr.
**E-mail:** lcavamura@gmail.com

---

*© 2026 L. Cavamura Jr. — Todos os direitos reservados.*
