<div id="section_errors" class = "postbox">
        <div class="inside">
            <div id="design_customization_settings" class="format-settings">
                <div class="format-setting-wrap">
		
	<h2><?php _e('Common SMTP errors and reply codes','webritismtp');?></h2><hr>	
	
	<p><strong>Note:</strong><?php _e('To get these errors codes you have start the SMTP Debugging mode by adding  the code','webritismtp');?><code>$phpmailer->SMTPDebug=1;</code> at the end of the function <b>webriti_phpmailer_init_smtp</b> in file <b>webriti-smtp-mail.php</b> </p>
	
	<table class="tbl_error">
	
	<tr style="font-weight:bold;">
    <td><?php _e('CODE','webritismtp');?></td>
    <td><?php _e('MEANING','webritismtp');?></td>		
    <td><?php _e('HOW TO SOLVE IT / WHAT TO DO','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('101','webritismtp');?></td>
    <td><?php _e('The server is unable to connect.','webritismtp');?></td>		
    <td><?php _e('Try to change the servers name (maybe it was spelt incorrectly) or the connection port.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('111','webritismtp');?></td>
    <td><?php _e('Connection refused or inability to open an SMTP stream.','webritismtp');?></td>		
    <td><?php _e('This error normally refers to a connection issue with the remote SMTP server, depending on firewalls or misspelled domains. Double-check all the configurations and in case ask your provider.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('211','webritismtp');?></td>
    <td><?php _e('System status message or help reply.','webritismtp');?></td>		
    <td><?php _e('It comes with more information about the server.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('214','webritismtp');?></td>
    <td><?php _e('A response to the HELP command.','webritismtp');?></td>		
    <td><?php _e('It contains information about your particular server, normally pointing to a FAQ page.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('220','webritismtp');?></td>
    <td><?php _e('The server is ready.','webritismtp');?></td>		
    <td><?php _e('Its just a welcome message. Just read it and be happy that everything is working (so far)!','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('221','webritismtp');?></td>
    <td><?php _e('The server is closing its transmission channel. It can come with side messages like "Goodbye" or "Closing connection".','webritismtp');?></td>		
    <td><?php _e('The mailing session is going to end, which simply means that all messages have been processed.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('250','webritismtp');?></td>
    <td><?php _e('Its typical side message is "Requested mail action okay completed": meaning that the server has transmitted a message.','webritismtp');?></td>		
    <td><?php _e('The oppsite of an error: everything has worked and your email has been delivered.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('251','webritismtp');?></td>
    <td><?php _e('"User not local will forward": the recipients account is not on the present server, so it will be relayed to another.','webritismtp');?></td>		
    <td><?php _e('Its a normal transfer action. For other information check out our article on what is an SMTP server.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('252','webritismtp');?></td>
    <td><?php _e('The server cannot verify the user, but it will try to deliver the message anyway.','webritismtp');?></td>		
    <td><?php _e('The recipients email account is valid, but not verifiable. Normally the server relays the message to another one that will be able to check it.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('354','webritismtp');?></td>
    <td><?php _e('The side message can be very cryptic ("Start mail input end <CRLF>.<CRLF>"). Its the typical response to the DATA command.','webritismtp');?></td>		
    <td><?php _e('The server has received the "From" and "To" details of the email, and is ready to get the body message.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('420','webritismtp');?></td>
    <td><?php _e('"Timeout connection problem": there have been issues during the message transfer.','webritismtp');?></td>		
    <td><?php _e('This error message is produced only by GroupWise servers. Either your email has been blocked by the recipients firewall, or theres a hardware problem. Check with your provider.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('421','webritismtp');?></td>
    <td><?php _e('The service is unavailable due to a connection problem: it may refer to an exceeded limit of simultaneous connections, or a more general temporary problem.','webritismtp');?></td>		
    <td><?php _e('The server (yours or the recipients) is not available at the moment, so the dispatch will be tried again later.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('422','webritismtp');?></td>
    <td><?php _e('The recipients mailbox has exceeded its storage limit.','webritismtp');?></td>		
    <td><?php _e('Best is to contact contact the user via another channel to alert him and ask to create some free room in his mailbox.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('431','webritismtp');?></td>
    <td><?php _e('Not enough space on the disk, or an "out of memory" condition due to a file overload.','webritismtp');?></td>		
    <td><?php _e('This error may depend on too many messages sent to a particular domain. You should try again sending smaller sets of emails instead of one big mail-out.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('432','webritismtp');?></td>
    <td><?php _e('Typical side-message: "The recipients Exchange Server incoming mail queue has been stopped".','webritismtp');?></td>		
    <td><?php _e('Its a Microsoft Exchange Servers SMTP error code. You should contact it to get more information: generally its due to a connection problem.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('441','webritismtp');?></td>
    <td><?php _e('The recipients server is not responding.','webritismtp');?></td>		
    <td><?php _e('There is an issue with the users incoming server: yours will try again to contact it.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('442','webritismtp');?></td>
    <td><?php _e('The connection was dropped during the transmission.','webritismtp');?></td>		
    <td><?php _e('A typical network connection problem, probably due to your router: check it immediately.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('446','webritismtp');?></td>
    <td><?php _e('The maximum hop count was exceeded for the message: an internal loop has occurred.','webritismtp');?></td>		
    <td><?php _e('Ask your SMTP provider to verify what has happened.','webritismtp');?></td>
	</tr>
	
	<tr>
    <td><?php _e('447','webritismtp');?></td>
    <td><?php _e('Your outgoing message timed out because of issues concerning the incoming server.','webritismtp');?></td>		
    <td><?php _e('This happens generally when you exceeded your servers limit of number of recipients for a message. Try to send it again segmenting the list in different parts.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('449','webritismtp');?></td>
    <td><?php _e('A routing error.','webritismtp');?></td>		
    <td><?php _e('Like error 432, its related only to Microsoft Exchange. Use WinRoute.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('450','webritismtp');?></td>
    <td><?php _e('"Requested action not taken – The users mailbox is unavailable". The mailbox has been corrupted or placed on an offline server, or your email hasnt been accepted for IP problems or blacklisting.','webritismtp');?></td>		
    <td><?php _e('The server will retry to mail the message again, after some time. Anyway, verify that is working on a reliable IP address.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('451','webritismtp');?></td>
    <td><?php _e('"Requested action aborted – Local error in processing". Your ISPs server or the server that got a first relay from yours has encountered a connection problem.','webritismtp');?></td>		
    <td><?php _e('Its normally a transient error due to a message overload, but it can refer also to a rejection due to a remote antispam filter. If it keeps repeating, ask your SMTP provider to check the situation. (If you are sending a large bulk email with a free one that can be a common issue).','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('452','webritismtp');?></td>
    <td><?php _e('Too many emails sent or too many recipients: more in general, a server storage limit exceeded.','webritismtp');?></td>		
    <td><?php _e('Again, the typical cause is a message overload. Usually the next try will succeed: in case of problems on your server it will come with a side-message like "Out of memory".','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('471','webritismtp');?></td>
    <td><?php _e('An error of your mail server, often due to an issue of the local anti-spam filter.','webritismtp');?></td>		
    <td><?php _e('Contact your SMTP service provider to fix the situation.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('500','webritismtp');?></td>
    <td><?php _e('A syntax error: the server couldnt recognize the command.','webritismtp');?></td>		
    <td><?php _e('It may be caused by a bad interaction of the server with your firewall or antivirus. Read carefully their instructions to solve it.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('501','webritismtp');?></td>
    <td><?php _e('Another syntax error, not in the command but in its parameters or arguments.','webritismtp');?></td>		
    <td><?php _e('In the majority of the times its due to an invalid email address, but it can also be associated with connection problems (and again, an issue concerning your antivirus settings).','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('502','webritismtp');?></td>
    <td><?php _e('The command is not implemented.','webritismtp');?></td>		
    <td><?php _e('The command has not been activated yet on your own server. Contact your provider to know more about it.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('503','webritismtp');?></td>
    <td><?php _e('The server has encountered a bad sequence of commands, or it requires an authentication.','webritismtp');?></td>		
    <td><?php _e('In case of "bad sequence", the server has pulled off its commands in a wrong order, usually because of a broken connection. If an authentication is needed, you should enter your username and password.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('504','webritismtp');?></td>
    <td><?php _e('A command parameter is not implemented.','webritismtp');?></td>		
    <td><?php _e('Like error 501, is a syntax problem; you should ask your provider.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('510/511','webritismtp');?></td>
    <td><?php _e('Bad email address.','webritismtp');?></td>		
    <td><?php _e('One of the addresses in your TO, CC or BBC line does not exist. Check again your recipients accounts and correct any possible misspelling.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('512','webritismtp');?></td>
    <td><?php _e('A DNS error: the host server for the recipients domain name cannot be found.','webritismtp');?></td>		
    <td><?php _e('Check again all your recipients addresses: there will likely be an error in a domain name (like mail@domain.coom instead of mail@domain.com).','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('513','webritismtp');?></td>
    <td><?php _e('"Address type is incorrect": another problem concerning address misspelling. In few cases, however, its related to an authentication issue.','webritismtp');?></td>		
    <td><?php _e('Doublecheck your recipients addresses and correct any mistake. If everythings ok and the error persists, then its caused by a configuration issue (simply, the server needs an authentication).','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('523','webritismtp');?></td>
    <td><?php _e('The total size of your mailing exceeds the recipient servers limits.','webritismtp');?></td>		
    <td><?php _e('Re-send your message splitting the list in smaller subsets.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('530','webritismtp');?></td>
    <td><?php _e('Normally, an authentication problem. But sometimes its about the recipients server blacklisting yours, or an invalid email address.','webritismtp');?></td>		
    <td><?php _e('Configure your settings providing a username+password authentication. If the error persists, check all your recipients addresses and if you have been blacklisted.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('541','webritismtp');?></td>
    <td><?php _e('The recipient address rejected your message: normally, its an error caused by an anti-spam filter.','webritismtp');?></td>		
    <td><?php _e('Your message has been detected and labeled as spam. You must ask the recipient to whitelist you.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('550','webritismtp');?></td>
    <td><?php _e('It usually defines a non-existent email address on the remote side.','webritismtp');?></td>		
    <td><?php _e('Though it can be returned also by the recipients firewall (or when the incoming server is down), the great majority of errors 550 simply tell that the recipient email address does not exist. You should contact the recipient otherwise and get the right address.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('551','webritismtp');?></td>
    <td><?php _e('"User not local or invalid address – Relay denied". Meaning, if both your address and the recipients are not locally hosted by the server, a relay can be interrupted.','webritismtp');?></td>		
    <td><?php _e('Its a (not very clever) strategy to prevent spamming. You should contact your ISP and ask them to allow you as a certified sender. Of course, with a professional SMTP provider like turboSMTP you wont ever deal with this issue.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('552','webritismtp');?></td>
    <td><?php _e('"Requested mail actions aborted – Exceeded storage allocation": simply put, the recipients mailbox has exceeded its limits.','webritismtp');?></td>		
    <td><?php _e('Try to send a lighter message: that usually happens when you dispatch emails with big attachments, so check them first.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('553','webritismtp');?></td>
    <td><?php _e('"Requested action not taken – Mailbox name invalid". That is, there is an incorrect email address into the recipients line.','webritismtp');?></td>		
    <td><?php _e('Check all the addresses in the TO, CC and BCC field. There should be an error or a misspelling somewhere.','webritismtp');?></td>
	</tr>
	<tr>
    <td><?php _e('554','webritismtp');?></td>
    <td><?php _e('This means that the transaction has failed. Its a permanent error and the server will not try to send the message again.','webritismtp');?></td>		
    <td><?php _e('The incoming server thinks that your email is spam, or your IP has been blacklisted. Check carefully if you ended up in some spam lists, or rely on a professional SMTP service like turboSMTP that will nullify this problem.','webritismtp');?></td>
	</tr>
	
	</table>
	
				</div>
			</div>
		</div>
    </div>
