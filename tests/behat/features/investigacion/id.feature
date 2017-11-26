# language: es
@api @d8
Característica: ID de investigación es obligatorio y único
  El ID de investigación permite identificar inequívocamente a la investigación
  siempre que sea necesaria la automatización mediante procesos
  Como editor
  Es obligatorio incluir el ID de investigación y el ID debe ser único

  Antecedentes:
    Dado   que estoy conectado como usuario con rol "aurora_editor"
    Y      "investigacion" con contenido:
            | title | field_id |
            | zxcv  | zxcv     |

  Escenario: Es obligatorio el ID de investigación
    Cuando   voy a la página de inicio
    Y        hago click en "Agregar contenido"
    Y        relleno lo siguiente:
               | Investigación | Prueba |
               | Cuerpo| Prueba |
               | Dotación Económica | 1 |
    Y        pulso el botón "Guardar"
    Entonces debo ver un mensaje de error "El campo Id es obligatorio"

  Escenario: El ID de investigación debe ser único
    Cuando   voy a la página de inicio
    Y        hago click en "Agregar contenido"
    Y        relleno lo siguiente:
               | Investigación | Prueba |
               | Id | zxcv |
               | Cuerpo | Prueba |
               | Dotación Económica | 1 |
             # notar que el Id es repetido en Antecedentes
    Y        pulso el botón "Guardar"
    Entonces debo ver un mensaje de error "Ya existe un content con id zxcv"
