<h5>Request:</h5>
<div class="table-responsive" style="margin-bottom: 30px;">
<table class="table-striped" width="100%" cellpadding="10" cellspacing="0" border="1">
	<tr>
		<td>Method</td>
		<td>{{ $request->getMethod() }}</td>
	</tr>
	<tr>
		<td>URI</td>
		<td>{{ $request->getUri() }}</td>
	</tr>
	<tr>
		<td>IP</td>
		<td>{{ $request->getClientIp() }}</td>
	</tr>

	<tr>
		<td>Referer</td>
		<td>{{ $request->getClientIp() }}</td>
	</tr>
	<tr>
		<td>Is secure</td>
		<td>{{ $request->isSecure() ? 'true' : 'false' }}</td>
	</tr>
	<tr>
		<td>Is ajax</td>
		<td>{{ $request->ajax() ? 'true' : 'false' }}</td>
	</tr>
	<tr>
		<td>User agent</td>
		<td>{{ $request->server('HTTP_USER_AGENT') }}</td>
	</tr>
	<tr>
		<td>Content</td>
		<td>{{ nl2br(htmlentities($request->getContent())) }}</td>
	</tr>
</table>
</div>

<h5>Error information:</h5>
<div class="table-responsive" style="margin-bottom: 30px;">
<table class="table-striped" width="100%" cellpadding="10" cellspacing="0" border="1">
	<tr>
		<td>Date</td>
		<td>{{ date('F d, Y H:i:s A') }}</td>
	</tr>
	<tr>
		<td>Message</td>
		<td>{{ $e->getMessage() }}</td>
	</tr>
	<tr>
		<td>Code</td>
		<td>{{ $e->getCode() }}</td>
	</tr>
	<tr>
		<td>File</td>
		<td>{{ $e->getFile() }}</td>
	</tr>
	<tr>
		<td>Line</td>
		<td>{{ $e->getLine() }}</td>
	</tr>
</table>
</div>

<h5>Stack trace:</h5>
<pre>{{ $e->getTraceAsString() }}</pre>