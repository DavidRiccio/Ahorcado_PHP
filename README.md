# 🎮 El Ahorcado - Juego en PHP

Un juego clásico del Ahorcado implementado en PHP con arquitectura orientada a objetos, gestión de sesiones y sistema de categorías.


## 📋 Descripción

Este proyecto implementa el juego del Ahorcado con una interfaz web moderna y responsiva. El juego permite a los usuarios adivinar palabras de diferentes categorías, con un sistema de intentos limitados y persistencia de estado mediante sesiones PH.

## 🏗️ Arquitectura del Proyecto

El proyecto está estructurado en cuatro clases principales siguiendo el principio de responsabilidad única:

### 1️⃣ Clase `Game`

**Responsabilidad**: Gestiona toda la lógica del juego del Ahorcado.

#### Propiedades Privadas
- `array $usedLetters`: Almacena las letras que el jugador ha utilizado
- `string $word`: Palabra objetivo que el jugador debe adivinar (en MAYÚSCULAS)
- `int $maxAttempts`: Número máximo de intentos permitidos
- `int $attemptsLeft`: Intentos restantes en la partida actual

#### Métodos Públicos

**`__construct(string $word, int $maxAttempts = 6, ?array $state = null)`**
- Inicializa una nueva partida o restaura una partida existente
- Parámetros:
  - `$word`: Palabra a adivinar en MAYÚSCULAS
  - `$maxAttempts`: Número máximo de intentos (por defecto 6)
  - `$state`: Estado serializado para restaurar una partida guardada

**`guessLetter(string $letter): bool`**
- Procesa un intento de letra del jugador
- Normaliza la letra a MAYÚSCULA
- Ignora letras repetidas
- Reduce intentos si la letra no está en la palabra
- Retorna `true` si la letra es correcta, `false` en caso contrario

**`getMaskedWord(): string`**
- Devuelve la palabra con guiones bajos para letras no descubiertas
- Las letras acertadas se muestran en su posición correcta
- Ejemplo: "C_S_" para la palabra "CASA" con letras C y S descubiertas

**`getAttemptsLeft(): int`**
- Retorna el número de intentos restantes en la partida actual

**`getUsedLetters(): array`**
- Devuelve un array con todas las letras ya jugadas en MAYÚSCULAS

**`isWon(): bool`**
- Verifica si el jugador ha ganado
- Retorna `true` si todas las letras de la palabra han sido descubiertas

**`isLost(): bool`**
- Verifica si el jugador ha perdido
- Retorna `true` si no quedan intentos disponibles

**`getWord(): string`**
- Retorna la palabra objetivo completa en MAYÚSCULAS

**`toState(): array`**
- Serializa el estado actual del juego para persistencia en sesión
- Retorna un array con `attemptsLeft` y `usedLetters`

---

### 2️⃣ Clase `WordProvider`

**Responsabilidad**: Provee palabras aleatorias para el juego desde un archivo JSON con sistema de categorías.

#### Propiedades Privadas
- `string $category`: Categoría de palabras seleccionada
- `array $words`: Lista de palabras cargadas de la categoría
- `const WORDS_FILE`: Ruta al archivo JSON de palabras

#### Métodos Públicos

**`__construct(string $category = 'general')`**
- Inicializa el proveedor con una categoría específica
- Carga las palabras del archivo JSON automáticamente
- Lanza excepciones si el archivo no existe o la categoría no es válida.

**`randomWord(): string`**
- Retorna una palabra aleatoria de la categoría seleccionada
- La palabra se devuelve en MAYÚSCULAS
- Lanza excepción si no hay palabras disponibles

**`getCategory(): string`**
- Retorna el nombre de la categoría actual

**`static getAvailableCategories(): array`**
- Método estático que retorna todas las categorías disponibles en el archivo JSON
- Útil para generar menús de selección de categoría

#### Métodos Privados

**`loadWords(): void`**
- Carga las palabras desde el archivo JSON
- Valida la estructura del archivo y la existencia de la categoría
- Lanza excepciones detalladas en caso de errores

---

### 3️⃣ Clase `Storage`

**Responsabilidad**: Gestiona la persistencia del estado del juego en la sesión de PHP.

#### Propiedades Privadas
- `string $key`: Clave de namespace en `$_SESSION` (por defecto 'ahorcado')

#### Métodos Públicos

**`__construct(string $key = 'ahorcado')`**
- Inicializa el almacenamiento con un namespace específico
- Inicia la sesión PHP automáticamente si no está activa
- Crea el espacio de datos en `$_SESSION` si no existe

**`get(string $name, $default = null)`**
- Recupera un valor almacenado en la sesión
- Parámetros:
  - `$name`: Nombre de la clave a recuperar
  - `$default`: Valor por defecto si la clave no existe
- Retorna el valor almacenado o el valor por defecto

**`set(string $name, $value): void`**
- Guarda un valor en la sesión bajo el namespace del juego
- Parámetros:
  - `$name`: Nombre de la clave
  - `$value`: Valor a almacenar (puede ser cualquier tipo serializable)

**`reset(): void`**
- Elimina completamente el estado almacenado del juego
- Reinicializa el namespace vacío
- Útil para comenzar una nueva partida.

#### Métodos Privados

**`initSession(): void`**
- Verifica si la sesión está activa antes de iniciarla
- Inicializa el namespace en `$_SESSION` si no existe
- Previene errores de "headers already sent".

---

### 4️⃣ Clase `Renderer`

**Responsabilidad**: Genera el dibujo ASCII del ahorcado según los intentos restantes.

#### Métodos Públicos

**`ascii(int $attemptsLeft): string`**
- Genera el dibujo ASCII del ahorcado correspondiente al número de intentos
- Parámetros:
  - `$attemptsLeft`: Número de intentos restantes (0-6)
- Retorna una cadena HTML con el dibujo envuelto en etiqueta `<pre>`
- El dibujo se vuelve más completo a medida que disminuyen los intentos.

#### Etapas del Dibujo
- **6 intentos**: Solo la horca vacía
- **5 intentos**: Horca + cabeza
- **4 intentos**: Horca + cabeza + cuerpo
- **3 intentos**: Horca + cabeza + cuerpo + brazo izquierdo
- **2 intentos**: Horca + cabeza + cuerpo + ambos brazos
- **1 intento**: Horca + cabeza + cuerpo + brazos + pierna izquierda
- **0 intentos**: Figura completa (ahorcado)

---

## 🚀 Características Principales

### Sistema de Categorías
- Palabras organizadas por categorías temáticas
- Selección dinámica de categoría al iniciar el juego
- Fácil expansión agregando categorías al archivo JSON

### Persistencia de Estado
- El progreso del juego se guarda automáticamente en la sesión PHP
- El jugador puede cerrar el navegador y continuar más tarde
- Sistema de namespace para evitar conflictos en `$_SESSION`

### Interfaz Responsiva
- Diseño adaptable a dispositivos móviles y de escritorio
- Teclado virtual con todas las letras del alfabeto
- Feedback visual inmediato de letras usadas
- Animaciones y transiciones suaves

### Validación Robusta
- Prevención de letras duplicadas
- Normalización automática a MAYÚSCULAS
- Validación de entrada de usuario
- Manejo de excepciones para errores de archivo.

---

## 📁 Estructura de Archivos

```text
proyecto-ahorcado/
├── App/
│ ├── Game.php # Lógica del juego
│ ├── WordProvider.php # Proveedor de palabras
│ ├── Storage.php # Gestión de sesiones
│ └── Renderer.php # Renderizado ASCII
├── data/
│ └── words.json # Base de datos de palabras
├── index.php # Punto de entrada del juego
└── README.md # Este archivo
```



---

## 🔧 Requisitos

- PHP 8.0 o superior
- Extensiones PHP necesarias:
  - `json` (para manejo de archivo de palabras)
  - `session` (para persistencia de estado)

---

## 🎨 Capturas de Pantalla

### Diseño Inicial (Boceto)
<p align="center">
  <img width="600" src="/ahorcado1/src/images/boceto.png" alt="Boceto del diseño">
</p>

El boceto muestra la estructura planificada del juego con:
- Selector de categoría en la parte superior
- Información de intentos y letras usadas
- Área del dibujo ASCII del ahorcado
- Palabra enmascarada con guiones
- Teclado de letras interactivo

### Implementación Final
<p align="center">
  <img width="600" src="/ahorcado1/src/images/ahorcado1.png" alt="Boceto del diseño">
</p>

La implementación final incluye:
- Interfaz moderna y colorida
- Diseño responsive para móviles
- Animaciones y efectos visuales
- Sistema de feedback claro para el usuario

---

## 🚀 Extensiones Futuras

### Funcionalidades Opcionales Propuestas

1. **Sistema de Pistas**
   - Método que revela una letra aleatoria no descubierta
   - Coste: reduce un intento disponible
   - Útil para palabras difíciles

2. **Historial de Partidas**
   - Registro de partidas ganadas/perdidas en archivo
   - Estadísticas de victorias por categoría
   - Ranking de mejores jugadores

3. **Niveles de Dificultad**
   - Fácil: 8 intentos, palabras cortas
   - Normal: 6 intentos, palabras medianas
   - Difícil: 4 intentos, palabras largas

4. **Archivo de Configuración**
   - Configuración JSON para:
     - Número de intentos por nivel
     - Categoría predeterminada
     - Colores del tema
     - Velocidad de animaciones

---



¡Disfruta jugando al Ahorcado! 🎉
