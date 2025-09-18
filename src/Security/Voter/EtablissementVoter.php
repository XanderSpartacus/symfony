<?php

namespace App\Security\Voter;

use App\Entity\Etablissement;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EtablissementVoter extends Voter
{
    // Définition des permissions (attributs)
    public const EDIT = 'ETABLISSEMENT_EDIT';
    public const VIEW = 'ETABLISSEMENT_VIEW';
    public const DELETE = 'ETABLISSEMENT_DELETE';
    public const NEW = 'ETABLISSEMENT_NEW';
    public const LIST = 'ETABLISSEMENT_LIST';

    private AuthorizationCheckerInterface $security;

    public function __construct(AuthorizationCheckerInterface $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // Ce voter ne s'active que pour les permissions listées ci-dessous.
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE, self::NEW, self::LIST])
            && ($subject instanceof Etablissement || $subject === null);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false; // Accès refusé si pas connecté
        }

        // Règle n°1 : L'administrateur a tous les droits.
        // C'est la règle la plus simple et la plus sûre.
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // Règle n°2 : Le partenaire peut voir et éditer.
        if ($this->security->isGranted('ROLE_PARTNER')) {
            // On vérifie si la permission demandée est VIEW ou EDIT.
            if ($attribute === self::VIEW || $attribute === self::EDIT) {
                return true;
            }
        }

        // Règle par défaut : pour tous les autres cas, on refuse l'accès.
        return false;
    }
}
