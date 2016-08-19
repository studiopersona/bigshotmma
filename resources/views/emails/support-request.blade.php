<h1 style="font-size: 18px;color:#222;">Support Request</h1>
<p style="font-size:14px;color:#222;margin:18px 0;">
	A support request has been received from the BSMMA contact form.
</p>
<table style="width:100%;border-collapse:collapse">
	<tr>
		<td>Name</td>
		<td>{{ $firstname }} {{ $lastname }}</td>
	</tr>
	<tr>
		<td>Phone</td>
		<td>{{ $phone }}</td>
	</tr>
	<tr>
		<td>Email</td>
		<td>{{ $email }}</td>
	</tr>
	<tr>
		<td colspan="2">
			Requested help with:<br>
			{{ $need_help_with }}
		</td>
	</tr>
	<tr>
		<td colspan="2">
			{{ $extra_info }}
		</td>
	</tr>
</table>