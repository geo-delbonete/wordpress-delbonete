# Diseño — Barrio (nombre provisorio)

Estado: **borrador v0.1 para revisar con Geo y Fran.** Todavía no hay código.
Los números de este doc son **cualitativos a propósito** (↑ ↓, "rápido/lento"). Los valores reales se calibran en la Fase 2 con la simulación.

---

## 1. Qué es

Constructor de barrio isométrico, cozy pero con consecuencias reales, para celular. Arte ilustrado a mano. Pago único con posibles expansiones, sin publicidad ni mecánicas adictivas.

El centro es una escuela. Los chicos que pasan por ahí se vuelven vecinos adultos con oficio, y eso mueve el juego a lo largo de unos 30 años. El jugador **acompaña, no manda**.

## 2. Decisiones tomadas en la entrevista

| # | Tema | Decisión |
|---|------|----------|
| 1 | Tiempo | Por estaciones (4 por año). 30 años ≈ 120 turnos. |
| 2 | Acciones | Pocas por estación (2 o 3 "gestos"). Cada una pesa. |
| 3 | Chicos → adultos | Cada chico muestra una **vocación visible** desde chico. El jugador la alimenta o no, pero no la elige. |
| 4 | Horas | **Cada vecino tiene sus propias horas.** Pueden regalar excedente a la cultura. |
| 5 | Tamaño | El barrio **crece**: ~10 chicos entran por año, según qué tan bien esté la escuela. En 30 años son 200–300 personas. |
| 6 | Adultos | Ciclo de vida completo: envejecen, se retiran, se despiden (con despedida suave). |
| 7 | Desequilibrio | **Picante.** Falta de acuerdos, hambre, bullying, muertes (un maestro puede morir de hambre). |
| 8 | Colapso | El barrio puede quedar **al borde** (3–4 vecinos, escuela en ruinas) pero **nunca llega a cero**. |
| 9 | Información | **Números visibles**, mostrados con íconos monocromos simples (no planilla). Además, color, luz y sonido. |
| 10 | Arranque | Terreno casi vacío, 8–12 fundadores adultos, algo de comida y materiales, sin escuela. El jugador elige qué va primero. |
| 11 | Derechos | Dos capas: **Acuerdos** (recurso) y **Normas** (reglas que se adoptan). En la simulación se arranca por los Acuerdos. |

Escuela por niveles: **jardín → primaria → secundaria → terciaria**, se van sumando. Una generación completa tarda ~17–20 años, así que los primeros adultos formados en la escuela aparecen recién hacia el año 15–20. Hasta entonces el barrio lo sostienen los fundadores.

## 3. Las tres esferas y sus recursos

Base: la trimembración social de Steiner (ver sección 9). **No se nombra dentro del juego.**

### Economía — "lo que necesitamos" (fraternidad)
| Recurso | Qué es |
|---|---|
| Comida | Lo que evita el hambre. Se produce en huertas y se transforma en cocinas. |
| Materiales | Para construir y herramientas. |
| Energía | Mueve talleres y cocinas. |
| Salud | Cuerpos sanos: menos enfermedad, más años activos. |

### Derechos — "lo que acordamos" (igualdad)
| Recurso | Qué es |
|---|---|
| Acuerdos | Se acumula con asambleas, mediación y vecinos con vocación de mediar. Se gasta en decisiones grandes. |
| Justicia | Estado (no se gasta): qué tan parejo se siente el barrio. Afecta conflicto y bullying. |
| Normas | Reglas vigentes. Cada una cambia cómo funciona el resto (ver sección 6). |

### Cultura — "lo que nos hace humanos" (libertad)
| Recurso | Qué es |
|---|---|
| Saber | Aprender y ciencia. Lo genera la escuela, la biblioteca y el laboratorio. |
| Expresión | Arte. |
| Encuentro | Celebrar, estar juntos. |

### Cómo se mide el equilibrio (propuesta, a probar en Fase 2)
La "salud del barrio" **no es la suma** de las tres esferas, porque una suma deja compensar un desastre con un exceso. Propongo que la salud esté **limitada por la esfera más baja** y que la brecha entre la más alta y la más baja sea lo que se ve como desequilibrio.
Por qué: es la traducción directa de "si una esfera domina, las otras sufren". Si lo implementamos como suma, el juego se resuelve produciendo comida y nada más. Esto se valida con la simulación.

## 4. Las horas (la moneda)

### Cómo funcionan (propuesta)
- Cada vecino adulto **gana horas** por estación según su oficio y estado (trabajo y cuidado cuentan igual).
- Cubre con ellas sus necesidades básicas (comida, techo).
- Lo que le sobra lo **ahorra** o lo **regala** a la cultura (un fondo cultural del barrio que financia Saber, Expresión y Encuentro).
- Chicos y mayores retirados aportan cuidado y mentoría, que también suma.

### ¿Funciona que las horas guardadas "se enfríen"?

**Qué dice la evidencia, y hasta dónde llega:**
- Wörgl (Austria, 1932): moneda con 1% mensual de pérdida, 32.000 chelines en total; el pueblo mejoró sus finanzas y el experimento lo frenaron las autoridades nacionales en 1933. Pero **no está claro cuánto se debió al envejecimiento del dinero**: los críticos señalan que pudo ser el efecto de una moneda local y la necesidad de pagar impuestos con ella. Las fuentes a favor son mayormente de partidarios de Gesell, y las contrarias de la escuela austríaca. Hay que leerlas con cuidado.
- Chiemgauer (Alemania, inspirado en Steiner y Gesell): hay estudios que comparan la velocidad de circulación entre monedas complementarias (De la Rosa y Stodder, 2015), pero **no pude verificar cifras concretas del Chiemgauer** en esta búsqueda. Un estudio de caso (Hayashi, 2021) argumenta que el Chiemgauer va más allá de acelerar la circulación y que el **regalo** es central.
- Conclusión honesta: la base empírica de que el envejecimiento *causa* más circulación es **débil o discutida**. Sirve como inspiración de diseño, no como prueba.

**Evaluación para el juego:**
- Lo bueno: crea una razón para que las horas circulen y para que el excedente llegue a la cultura. Encaja con la idea de regalo.
- El riesgo: pérdida pura = castigo. En un juego cozy puede sentirse como una presión permanente ("se me enfrían las horas") y a los vecinos mayores o cuidadosos los castiga injustamente.

**Propuesta (a probar en Fase 2 con interruptor on/off):**
1. El enfriamiento aplica **solo al excedente** por encima de una reserva personal (una "franquicia" que cubre unas estaciones de necesidades básicas). Quien ahorra lo justo no pierde nada.
2. Las horas que se enfrían **no desaparecen**: pasan al fondo cultural del barrio. Así la pérdida de uno es el regalo de todos. Se ve como "tus horas se fueron a la fiesta", no como "perdiste".
3. Los mayores retirados no se enfrían.

**Alternativas si no funciona:**
- A) Sin enfriamiento; solo un **tope de ahorro** (lo que pasa del tope se dona solo).
- B) **Rendimiento del regalo:** en vez de castigar guardar, regalar a la cultura da algo a cambio (reconocimiento, Encuentro). Premio en vez de castigo.
- C) Enfriamiento **estacional** (cada invierno se reparte el excedente), ritual en lugar de goteo constante.

## 5. Construcciones y gestos

Regla del diseño: casi todo tiene **un costo hoy en una esfera y un beneficio diferido en otra**. La columna "Demora" dice cuánto tarda en notarse (E = estaciones, A = años).

### Construcciones

| Construcción | Esfera | Costo hoy | Beneficio diferido | Demora |
|---|---|---|---|---|
| **Huerta comunitaria** | Economía | Materiales, horas de trabajo; ocupa terreno | Comida estable; menos hambre. Si falta cuidado, rinde menos | 2–3 E |
| **Cocina / panadería comunal** | Economía | Materiales, energía | Menos horas por comida; sube Encuentro | 2 E |
| **Taller** | Economía | Materiales; horas que no producen comida | Productividad de oficios; herramientas | 1–2 A |
| **Casas** | Economía | Materiales | Más capacidad para recibir gente | 1–2 E |
| **Posta de salud** | Economía | Horas de cuidado altas; requiere Saber | Menos muertes por enfermedad; adultos activos más años | 1–3 A |
| **Energía (molino / solar)** | Economía | Materiales; requiere Saber | Energía para talleres y cocinas | 1–2 A |
| **Plaza de asambleas** | Derechos | Horas; baja la producción durante las reuniones | Acuerdos; baja el conflicto | 2–4 E |
| **Círculo de mediación** | Derechos | Horas de mediadores (que no producen) | Menos bullying y peleas; sube Justicia | 1–2 A |
| **Registro de bienes comunes** | Derechos | Acuerdos, horas | Huertas y plazas de la escuela se cuidan mejor (ver Ostrom) | 1–3 A |
| **Escuela (jardín)** | Cultura | Materiales ↓↓, **Acuerdos ↓↓**, horas | Más acuerdos y Saber; chicos con vocación | 3–5 A |
| **Primaria / secundaria / terciaria** | Cultura | Materiales, horas de maestros | Vecinos adultos con oficio calificado; **la generación se completa** | 5–20 A |
| **Biblioteca** | Cultura | Materiales, horas | Saber estable; ayuda a todos los niveles | 1–2 A |
| **Taller de arte / anfiteatro** | Cultura | Horas que no producen comida | Expresión y Encuentro; vocaciones creativas | 1–3 A |
| **Laboratorio / observatorio** | Cultura | Energía, Saber, horas | **Ciencia aplicada**: mejora la eficiencia de la economía (cultura → economía) | 3–8 A |

### Gestos (acciones que no son construir)

| Gesto | Esfera | Costo hoy | Beneficio diferido |
|---|---|---|---|
| **Organizar una fiesta** | Cultura | Comida, horas de todos | Encuentro; baja tensión; refuerza vínculos |
| **Convocar una asamblea** | Derechos | Horas, pausa de producción | Acuerdos; destraba una decisión |
| **Regalar horas a la cultura** | Cultura | Horas de la economía | Financia Saber, Expresión, Encuentro |
| **Becar / apoyar a un chico** | Cultura | Horas, a veces comida | Su vocación florece; sube su oficio de adulto |
| **Proponer una norma** | Derechos | Acuerdos | Cambia reglas (ver sección 6) |
| **Reforzar el cuidado de un vecino** | Economía | Horas ajenas | Evita crisis (hambre, agotamiento) |
| **Abrir cupos de escuela** | Cultura | Comida, casas, plazas | Más chicos entran por año |

### Freno natural al crecimiento
Una escuela buena atrae más chicos, y más chicos hacen más fuerte la escuela. Eso puede disparar el barrio. El freno: **plazas de escuela, casas y comida** limitan cuántos entran por año. Una escuela buena en un barrio sin comida no crece.

## 6. Normas (capa de Derechos)

Reglas que el barrio adopta y que modifican las reglas del juego. Cada una tiene costo hoy y beneficio diferido, y **varias juntas pueden chocar**. Ejemplos para empezar:

| Norma | Costo | Beneficio |
|---|---|---|
| El excedente se reparte parejo | Los productivos ganan menos; cuesta Acuerdos | Menos hambre y más Justicia, de a poco |
| Nadie trabaja más de X horas por estación | Baja la producción hoy | Menos agotamiento; más cuidado y cultura después |
| La escuela es abierta a todos | Más gasto en comida y plazas | Más vocaciones distintas, más cultura |
| Los bienes comunes tienen reglas de uso | Cuesta Acuerdos y horas de control | Menos sobreuso de huertas y espacios (ver Ostrom) |

Se implementan en una segunda capa: primero la simulación con Acuerdos como recurso, después las normas.

## 7. Cómo se ve el desequilibrio

**Estado general:** cuatro canales — color, luz, sonido y **números con íconos** (decisión 9). Cada vecino se puede tocar para ver su estado y el porqué ("Rosa está cansada: lleva dos años sin descanso").

### Cuando una esfera domina

| Si domina… | Se ve | Se oye | Se siente en el juego |
|---|---|---|---|
| **Economía** | Colores saturados pero planos; todo gris-marrón alrededor de los talleres | Ruido de taller constante | La cultura se seca: chicos con menos vocaciones creativas, fiestas que no se hacen |
| **Derechos** | Orden grisáceo, silencio, todo parejo | Silencio, pasos | Las decisiones cuestan más Acuerdos; el arte languidece |
| **Cultura** | Brillos y color exagerados, huertas descuidadas | Música, sin cocina ni herramientas | Vecinos soñadores; falta comida; la belleza no se sostiene |

### Cuando una esfera falta (el "picante")
Siempre en **tres pasos: aviso → consecuencia → crisis**, y siempre justo (el jugador pudo verlo venir).

| Falta… | Aviso | Consecuencia | Crisis |
|---|---|---|---|
| Comida | Vecinos flacos, huertas secas, color apagado | Menos horas de trabajo, enfermedades | **Hambre; un vecino (un maestro, por ejemplo) puede morir** |
| Acuerdos / Justicia | Murmullos, miradas | Peleas, vecinos que se van | **Bullying en la escuela**; asamblea tensa; ruptura |
| Cultura | Tristeza, silencio, fiestas que no se hacen | Chicos sin vocaciones; los adultos pierden sentido | Éxodo de jóvenes; la escuela se vacía |

**Reglas para que sea justo:**
- Ninguna muerte por negligencia ocurre sin varias estaciones de avisos visibles.
- Hay **piso**: el barrio puede quedar al borde, nunca en cero.
- Las crisis siempre ofrecen un **gesto para responder**; nunca son solo castigo.
- Muertes naturales (vejez) y por negligencia se tratan **distinto**: las primeras con despedida suave, las segundas con peso.

**Sobre el bullying:** conviene tratarlo como algo que el barrio ve y acompaña (la escuela como lugar de resolución), no como espectáculo. Esto puede afectar la clasificación por edades de las tiendas (ver sección 9).

## 8. Efectos que se ven recién con los años

El motor de largo plazo son las generaciones:
- La escuela cambia **qué vocaciones salen** años después. Un barrio sin cultura produce adultos sin artistas ni maestros.
- Un chico sin cuidado en la secundaria vuelve a la comunidad como un adulto con menos oficio o más conflictivo.
- Falta de maestros hoy = faltan maestros dentro de 20 años (la escuela no se reproduce a sí misma).
- Decisiones de la primera década (por ejemplo, no construir una posta de salud) pesan sobre los adultos de la tercera.

Los chicos con vocación se ven **años antes**, así que el jugador puede anticipar lo que le va a faltar.

## 9. Investigación (con fuentes)

### 9.1 Trimembración social (Steiner)
Steiner (1919) propone tres esferas autónomas: **cultural** (libertad), **de derechos** (igualdad) y **económica** (fraternidad). Cuando una domina a las otras, la sociedad se daña: ejemplos clásicos son la teocracia (domina la cultura), el capitalismo sin regulación (domina la economía) y el socialismo de Estado (domina la política). Algunos autores la presentan como una **lente de interpretación** más que como un sistema lógico cerrado.
- [Social threefolding (resumen)](https://anthroposophie.ch/en/economy-society/topics/articles/social-threefolding.html)
- [Threefold Social Order — Rudolf Steiner Web](https://rudolfsteinerweb.com/Threefold_Social_Order.php)
- [The Threefold Commonwealth (texto, Project Gutenberg)](https://gutenberg.org/cache/epub/59257/pg59257-images.html)
- [Economía asociativa — Goetheanum](https://economicsnew.goetheanum.org/research/associative-economics-2)

*Pendiente:* verificar en el texto original de Steiner (Curso de economía) lo que dice puntualmente sobre el "dinero que envejece"; en esta búsqueda no lo pude confirmar directamente.

### 9.2 Dinero que envejece y monedas complementarias
- Wörgl: ver sección 4. [Comentario de Greco](https://reinventingmoney.com/greco-comment_on_worgl/) (crítico del rol del envejecimiento) y [Mises Institute](https://mises.org/library/free-money-miracle) (escéptico); las fuentes a favor suelen ser de partidarios.
- Chiemgauer y velocidad de circulación: [De la Rosa y Stodder (2015), IJCCR](https://www.ijccr.net/article/view/9192); estudio de caso con foco en el regalo y Steiner: Hayashi (2021), citado en la búsqueda.
- **Qué no hay:** evidencia sólida y consensuada de que el envejecimiento causa más circulación. Tratar como hipótesis a probar en la simulación.

### 9.3 Bienes comunes (Ostrom)
Los 8 principios de diseño (*Governing the Commons*, 1990): 1) límites claros, 2) reglas ajustadas a lo local, 3) elección colectiva, 4) monitoreo, 5) sanciones graduales, 6) resolución de conflictos barata y local, 7) reconocimiento externo del derecho a organizarse, 8) gobierno en capas. **Salvedad:** investigaciones posteriores ya no los usan como test rígido de éxito.
- [P2P Foundation — los 8 principios](https://wiki.p2pfoundation.net/Elinor_Ostrom’s_Eight_Commons_Governance_Design_Principles)
- [Patterns of Commoning](https://patternsofcommoning.org/uncategorized/eight-design-principles-for-successful-commons/)

**Cómo los usamos:** las huertas, la cocina comunal y las plazas de la escuela son bienes comunes. Las Normas pueden ser versiones de estos principios (reglas de uso, sanciones graduales, mediación barata). Es lo que da profundidad a la esfera de derechos.

### 9.4 Cómo resuelven balance los constructores cozy y de sistemas
La búsqueda trajo **más reseñas que análisis de diseño**; lo siguiente es lo que se pudo respaldar.
- **Dorfromantik:** pilares de diseño de minimalismo, legibilidad y relajación; baja barrera de entrada; arte inspirado en arte naíf e impresionismo. ([80.lv](https://80.lv/articles/how-dorfromantik-expands-its-cozy-world-through-minimalist-design))
- **Islanders:** su atractivo viene de sacar la gestión compleja y dejar una colocación tipo puzzle; las reseñas marcan que el "sin fallar" no elimina la repetición. ([Vaporlens](https://vaporlens.app/app/1046030/islanders))
- **Banished:** la hambruna es de logística (sin caminos ni depósitos, los trabajadores pasan hambre); enseña a través del fracaso. Una crítica señala que casi no castiga el agotamiento de recursos. ([GameSpot](https://www.gamespot.com/reviews/banished-review/1900-6415679/), [Game Informer](https://www.gameinformer.com/games/banished/b/pc/archive/2014/02/17/banished-review-winter-is-coming.aspx), [PlayThePast](https://www.playthepast.org/?p=5805))
- **Frostpunk:** capa de leyes con dilemas morales sobre la capa de supervivencia.
- **Dwarf Fortress:** simulación en capas (historia, emociones, consecuencias sobre consecuencias). Fuente débil.
- **Townscaper:** sin presión ni fallo, sandbox. **No encontré detalles de diseño**; falta una búsqueda específica.

**Lecciones que tomo (son interpretación mía, no hechos de las fuentes):**
1. Las crisis solo resultan justas si la causa se puede ver (Banished con logística visible).
2. La capa de normas con dilemas (Frostpunk) sirve para dar peso moral a las decisiones.
3. Un cozy con reglas muy flexibles tiende a la repetición; el costo/beneficio diferido es lo que le da dirección.

### 9.5 Mercado: qué se vende y cómo
- **Pago único puede funcionar a gran escala:** *Monument Valley 2* vendió unos 3,5 millones de copias y ~10,4 millones de USD en su primer año, casi el doble que el primer año del original. ([MCV](https://mcvuk.com/business-news/monument-valley-2-sells-3-5m-and-brings-in-10m-revenue-over-first-year/)) Según [MCV](https://www.mcvuk.com/development/monument-valley-racks-up-over-21m-downloads-in-2015), el original tuvo más de 21 millones de descargas en 2015, de las cuales una parte importante fue gratis.
- **Premium crece pero desde una base chica:** según datos de AppMagic citados por Pocket Tactics, los lanzamientos premium crecieron 77% interanual en 2025, pero el free-to-play sigue siendo el 96% de las descargas. ([Pocket Tactics](https://www.pockettactics.com/premium-mobile-games-increase))
- **Precio:** circula "~14,99 USD es el número mágico del cozy", pero es una opinión de un blog, **sin respaldo**. No lo tomo como dato.
- **Suscripción (Apple Arcade, Play Pass):** existen desde 2019, pero **no encontré datos de ingresos** para evaluarlas.
- **Edades:** Apple cambió su sistema en 2025 a 4+, 9+, 13+, 16+ y 18+, con un cuestionario que incluye temas violentos. ([MacRumors](https://www.macrumors.com/2025/07/25/apple-overhauls-app-store-age-ratings/)) **No pude verificar cómo se clasificaría un juego con muerte por hambre y bullying;** hay que mirar el cuestionario real y las guías de Google Play/IARC antes de diseñar el arte de esos eventos.

**Lo que NO pude responder:** si un cozy premium vende de forma **sostenida** (cola larga de años) o solo hace un pico de lanzamiento; no hay datos de cozy builders en celular. Para tu objetivo de ventas sostenidas, esto es el punto con menos base. Lo que sí sostiene la idea: expansiones, actualizaciones y presencia en las tiendas, pero eso es hipótesis.

## 10. Stack propuesto (a validar con Fran)

- **Núcleo de simulación en TypeScript**, sin interfaz, que corre en consola con Node y tiene tests (Vitest). Es el que vamos a usar en la Fase 2.
- **Prototipo visual:** PixiJS (2D, bueno para isométrico) dentro de una app envuelta con **Capacitor** para iOS y Android.
- Por qué: Fran es dev web y apps, así que TypeScript lo lee sin curva. El núcleo queda **separado** de la pantalla, así los tests no dependen del arte y el mismo código corre en el celular.
- Riesgo: rendimiento en celulares viejos con WebView y muchas animaciones ilustradas. Se prueba en la Fase 3 con una prueba de carga antes de seguir. La alternativa si falla es **Godot 4** (exporta a iOS y Android, muy bueno en 2D), a costa de que Fran aprenda otra herramienta.
- **No está investigado con fuentes**; es criterio técnico mío. Fran tiene la última palabra.

## 11. Restricciones

- **Constelación y "la vida misma":** no se usan sus textos ni nombres hasta que Geo confirme los derechos.
- Sin publicidad invasiva ni mecánicas para generar hábito compulsivo (nada de "volvé todos los días", energía que se recarga con espera, ni recompensas diarias).
- Pago único con posibles expansiones.
- Priorizar la experiencia del jugador sobre la comodidad técnica.

## 12. Preguntas abiertas

1. **Enfriamiento de horas:** ¿se siente bien o como castigo? Se prueba en la Fase 2 con on/off.
2. **Equilibrio como mínimo de las esferas**, ¿es interesante o frustra? Se prueba en la Fase 2.
3. **Qué tan duro es el hambre:** ¿cuántas estaciones de aviso antes de la primera muerte por negligencia?
4. **Clasificación por edades:** cómo se clasifica con bullying y muertes; condiciona cómo se muestran.
5. **Reproducción de la escuela:** ¿los maestros salen de la propia escuela (terciaria) o llegan de afuera al principio?
6. **Interfaz del barrio con 200–300 personas:** vista por grupos, con seguimiento cercano solo de quienes pasaron por la escuela. A definir en la Fase 3.
7. **Mercado:** ¿se sostiene en el tiempo un cozy premium? Falta información. Qué precio, qué expansiones.
8. **Derechos de Constelación:** pendiente de confirmación de Geo.
9. **Nombre del juego.**
10. **Fundadores:** ¿tienen oficios fijos o los elige el jugador al arrancar?
