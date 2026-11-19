<h1>Bienvenido(a) a nuestro Sistema de Viaticos</h1>

<p>Estimado(a) usuario(a):</p>

<p>Le informamos que su cuenta ha sido creada con éxito. A continuación, encontrará sus credenciales de acceso iniciales:</p>

<ul>
    <li><strong>Usuario (Correo Electrónico):</strong> <code>{{ $mail }}</code></li>
    @if(!empty($randomPassword))
    <li><strong>Contraseña Temporal:</strong> <code>{{ $randomPassword }}</code></li>
    @endif
</ul>

<p>Puede acceder a la plataforma a través de la siguiente liga:</p>
<p><a href="https://sspcsistemaviaticos.sspc.gob.mx/login">https://sspcsistemaviaticos.sspc.gob.mx/login</a></p>

<h2>Documentación Institucional Adjunta</h2>

<p>
    Por favor, preste especial atención al archivo adjunto en este correo:
</p>
<p>
    Encontrará el documento "Carta_Compromiso_Institucional.docx", el cual formaliza sus datos institucionales y contiene los fundamentos legales aplicables.
</p>
<p>
    Le solicitamos descargar y revisar dicho documento para su conocimiento y los fines que el área requiera.
</p>

<hr style="border: 0; border-top: 1px solid red; margin: 20px 0;">

<p><strong>Importante:</strong> Por motivos de seguridad, le solicitamos encarecidamente cambiar su contraseña temporal
    en su primer inicio de sesión.</p>

<p>Agradecemos su atención y le damos la bienvenida.</p>

<p>Atentamente,</p>
<p>Dirección General de Programación y Presupuesto.</p>
