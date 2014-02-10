Framework de Programación Genética
==================================

**Version:** 1.0.1

**Última revisión:** 2014-02-10

**Autores:**
- Adrian Will
- Soledad Elli
- 
- Adrian Jimenez

El proyecto esta desarrollado en Qt Creator, y funciona tanto en linux como en Windows. 

**Nota:** para mas detalles sobre como instalar Qt Creator leer el archivo "Instrucciones.pdf".

Compilar código en Qt Creator
-----------------------------
1. El sistema está compuesto por 2 proyectos. Por un lado el framework de algoritmos genéticos, y por otro el proyecto de programación genética en sí. En primer lugar hay que compilar el framework. Para ello abrimos Qt Creator y abrimos el archivo *FrameworkQT.pro* ubicado en el directorio *Framework*.
2. Una vez abierto, verificamos la configuración desde el panel izquierdo. El campo *Build directory* debe tener el valor *ProyectoQT*.
3. Procedemos a compilar el código fuente desde el panel izquierdo.
4. Ya podemos cerrar el proyecto, ya que no es necesario compilar lo nuevamente.
5. Repetimos el **punto 1**, pero esta vez abrimos el archivo *RegresionQT.pro* ubicado en la carpeta “Regresion”, correspondiente al proyecto de Programación Genética.
6. Una vez abierto, verificamos la configuración. El campo *Build directory* debe tener el valor *ProyectoQT*.
7. Procedemos a compilar y correr el programa.
Para mas detalles leer el archivo *Instrucciones.pdf* ubicado en el directorio principal del repositorio.

Dependencias
------------

El proyecto utiliza dos librerías

* *RapidXML*: para procesar archivos XML.
* *Armadillo*: librería de algebra lineal.

El codigo fuente viene incluido, por lo que no es necesario instalar dichas librerias.

Archivo de Configuración
------------------------
Desde la versión 1.0.1, el sistema viene con la opción de cargar los parámetros del algoritmo mediante un archivo XML. 

De esta forma, si por ejemplo el ejecutable compilado se llama *RegresionQT.exe*, un usuario puede ejecutar el programa desde la consola de la siguiente forma:

    RegresionQT.exe configuraciones/Config.XML
    
Donde *configuraciones/Config.XML* es la ruta del archivo de configuración a utilizar.

Un ejemplo del contenido del archivo de configuración se muestra a continación:

    <?xml version="1.0" encoding="UTF-8" standalone="no"?>
    <root>

    <Genetico  algoritmo="SIMPLE" pop="120" gens="300" eval="100" sel="100" xov="55" mut="10" elites="5" evaluacion="NORMA" verbose="0"  tablaX="configuracion/TablaXY.xml" datos="datos_entrada/seno_amortiguado.csv" />
    
    <Soluciones limite_fitness="0.01" altura_minima="0" altura_maxima="9" altura="9" penalizacion="1" />
    
    <Constantes metodo="HC" bound1="-10" bound2="10" />
    
    <HC itNeg="10" lambda="1" maxEps ="1.2" />
    
    <SA itNeg="10" lambda="1" maxEps ="1.2" temp="2" mu="0.05" />
    
    <MDHC maxEps ="1.2" itNeg="10" lambda="1" temp="2" mu="0.05" />
    
    <MDHC_all maxEps ="1.2" itNeg="10" lambda="1" temp="2" mu="0.05" />
    
    <DE F="0.5" CR="1" tamPop="10" genMax="10" />
    
    <NM tempCte="2" porc="5" />
    
    <GA se="1" genMax="20" />
        
    </root>
