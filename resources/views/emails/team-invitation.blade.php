{{-- resources/views/emails/team-invitation.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation à rejoindre une équipe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3B82F6;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            background-color: #3B82F6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Masadigitale Forms</h1>
    </div>

    <div class="content">
        <h2>Vous avez été invité à rejoindre une équipe !</h2>

        <p>Bonjour,</p>

        <p><strong>{{ $inviter->name }}</strong> vous invite à rejoindre son équipe sur Masadigitale Forms avec le rôle de <strong>{{ $invitation->role }}</strong>.</p>

        <p>En rejoignant cette équipe, vous pourrez collaborer sur les formulaires et gérer les réponses ensemble.</p>

        <div style="text-align: center;">
            <a href="{{ route('team.accept', $invitation->token) }}" class="button">
                Accepter l'invitation
            </a>
        </div>

        <p>Ou cliquez sur ce lien : <a href="{{ route('team.accept', $invitation->token) }}">{{ route('team.accept', $invitation->token) }}</a></p>

        <p>Si vous n'êtes pas intéressé, vous pouvez ignorer cet email ou <a href="{{ route('team.decline', $invitation->token) }}">refuser l'invitation</a>.</p>

        <p>Cette invitation expirera le {{ $invitation->expires_at->format('d/m/Y à H:i') }}.</p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Masadigitale Forms. Tous droits réservés.</p>
    </div>
</body>
</html>
