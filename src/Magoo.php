<?php

declare(strict_types=1);

namespace Pachico\Magoo;

/**
 * Magoo will mask sensitive data from strings.
 */
class Magoo implements MaskManagerInterface
{
    protected array $masks = [];

    /**
     * Adds (or rewrites) Mask\CreditCard mask
     *
     * @param string $replacement Character to replace matches
     *
     * @return Magoo
     */
    public function pushCreditCardMask(string $replacement = '*'): self
    {
        $this->masks['mask-creditcard'] = new Mask\Creditcard(
            [
                'replacement' => $replacement
            ]
        );

        return $this;
    }

    /**
     * Adds Mask\Regex mask
     *
     * @param string $regex Regex to be executed
     * @param string $replacement Character to replace matches
     *
     * @return Magoo
     */
    public function pushByRegexMask(string $regex, string $replacement = '*'): self
    {
        $uniqueId = uniqid('mask-regex-');

        $this->masks[$uniqueId] = new Mask\Regex(
            [
                'regex' => empty($regex) ? '/^$/' : (string) $regex,
                'replacement' => (string) $replacement
            ]
        );

        return $this;
    }

    /**
     * Adds (or rewrites) Mask\Email mask
     *
     * @param string $localReplacement Character to replace local part of email
     * @param string|null $domainReplacement Character to replace domain part of email
     *
     * @return Magoo
     */
    public function pushEmailMask(string $localReplacement = '*', ?string $domainReplacement = null): self
    {
        $params = [
            'localReplacement' => null,
            'domainReplacement' => null,
        ];

        $this->masks['mask-email'] = new Mask\Email(
            array_merge($params, [
                'localReplacement' => $localReplacement,
                'domainReplacement' => $domainReplacement,
            ])
        );

        return $this;
    }


    /**
     * {@inheritDoc}
     */
    public function pushMask(Mask\MaskInterface $customMask): self
    {
        $uniqueId = uniqid('mask-custom-');
        $this->masks[$uniqueId] = $customMask;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function reset(): self
    {
        $this->masks = [];

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMasked(string $input): string
    {
        if (empty($this->masks)) {
            return $input;
        }

        foreach ($this->masks as $mask) {
            $input = $mask->mask($input);
        }

        return $input;
    }
}
